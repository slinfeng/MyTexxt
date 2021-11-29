<?php

namespace App\Http\Controllers;

use App\Constants\RedisKey;
use App\Models\AccountsInvoice;
use App\Models\AdminSetting;
use App\Models\AssetInfo;
use App\Models\Attendance;
use App\Models\Client;
use App\Models\EmployeeBase;
use App\Models\Leave;
use App\Models\ScheduleGroup;
use App\Traits\NotifyTrait;
use ErrorException;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Jenssegers\Agent\Facades\Agent;
use Goutte\Client as Spider;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;

class   HomeController extends Controller
{
    use NotifyTrait;
    /**
     * Show the application dashboard.
     *
     * @return Application|RedirectResponse|Redirector
     */
    public function index()
    {
        if (Agent::isMobile()) {
            if(Gate::denies('mobile_login')){
                Auth::logout();
                $view=route('login');
                $loginMessage='スマホ側ログイン権限がありません。';
                return redirect($view)->with('loginMessage',$loginMessage);
            }
            if(Gate::allows('mobile_modify')){
                if(EmployeeBase::where('user_id',Auth::id())->count()<1){
                    if(Gate::allows('mobile_audit')){
                        return redirect()->to(route('audit.home'));
                    }else{
                        Auth::logout();
                        $view=route('login');
                        $loginMessage='社員情報なし、担当者までご連絡ください。';
                        return redirect($view)->with('loginMessage',$loginMessage);
                    }
                }else{
                    $notification = $this->getNotifications(Auth::id());
                    $notifications = $notification->get();
                    $employee = EmployeeBase::with('employeeContacts','hireType','departmentType','positionType')->where('user_id',Auth::id())->first();
                    $this->changeNotificationStatus($notification);
                    return view('mobile.personal.home',compact('notifications','employee'));
                }
            }
            if(Gate::allows('mobile_audit')){
                return redirect()->to(route('audit.home'));
            }
        }else {
            if(Gate::denies('pc_login')){
                Auth::logout();
                $view=route('login');
                $loginMessage='pc側ログイン権限がありません.';
                return redirect($view)->with('loginMessage',$loginMessage);
            }
            if(Gate::allows('invoice_self_modify')){
                return redirect(route('invoice.index'));
            }else{
                return view('home',$this->getStatistical(true,true));
            }
        }
    }

    public function mobileAudit(){
        $employees = EmployeeBase::with("user:id,name,email",'hireType','departmentType')->where("modified_type","1")->get();
        $attendances = Attendance::where('status',0)->get();
        $leaves=Leave::with('EmployeeBase')->where('status',0)->get();
        return view('mobile.examination.home',compact('employees','attendances','leaves'));
    }

    public function getStatistical($init=false,$getNews=false){
        $year = $this->getFiscalYear($init);
        $flag = $year == 'curr';
        if($flag) $year = $this->getFiscalYear(true);
        $yearArr = [];
        for($i=0;$i<10;$i++){
            $yearArr[] = $year-$i;
        }
        $period = $this->getFiscalPeriod($year);
        $statistical['client_cooperation']=$this->getClientCooperationStatistical($period);
        $statistical['client_alliance']=$this->getClientAllianceStatistical($period);
        $statistical['employee']=$this->getEmployeeStatistical($period);
        $statistical['asset']=$this->getAssetStatistical($period);
        if(!$flag){
            $statistical['month_sales']=$this->getMonthSalesStatistical($year);
            $statistical['year_sales']=$this->getTenYearsSalesStatistical($year);
        }
        $statistical['fiscal_year']=$year;
        $statistical['fiscal_year_arr']=$yearArr;
        if($getNews){
//            $statistical['news']=$this->getNews();
//            $statistical['docs']=$this->getDocs();
            $statistical['showInfo']=$this->showInfo();
            $statistical['news_economy']=$this->getNewsFromYahoo();
            $statistical['news_international']=$this->getInternationalNewsFromYahoo();
        }
        return $statistical;
    }

    public function getCoinInfo(){
        $ids = 'bitcoin,xrp,ethereum,polkadot,tezos,stellar,nem,basic-attention-token,ethereum-classic,litecoin,bitcoin-cash,monacoin,lisk,eos';
        return $this->getCoinData($ids,0);
    }

    private function getCoinData($ids,$index){
        $url = 'https://api.coincap.io/v2/assets?ids='.$ids;
        $index++;
        if($index == 10) abort(500,'仮想通貨データの取得が失敗しました！');
        try{
            return file_get_contents($url);
        }catch (ErrorException $e){
            return $this->getCoinData($ids,$index);
        }
    }

    private function getRate(){
        $url = 'https://api.coincap.io/v2/assets';
    }

    public function getCoinYearData($id){

        $periodArr = ['-1 year','-6 month','-3 month'];
        $mode = $periodArr[$_GET['mode']];
        $disk = Storage::disk('local');
        $date=(date('Y')-1).'-'.date('m').'-01';
        $start = strtotime($date)*1000;
        $end = strtotime('now')*1000;
        $coin = $this->getCoinDataFromAPI($id,$start,$end,0);
        $arr = json_decode($coin);
        $temp = '';
        foreach ($arr->data as $key=>$obj){
            if($temp!=''){
                $temp.=',';
            }
            $obj->date = substr($obj->date,0,10);
            $objArr[] = $obj;
            $temp .= '{'.'"priceUsd":"'.$obj->priceUsd.'","time":'.$obj->time.',"date":"'.$obj->date.'"}';
        }
//        $path = $disk->path('coins/'.$id.'.json');
//        file_put_contents($path,$temp);
//        try {
//            if(!$disk->exists('coins/'.$id.'.json'))
//                $disk->put('coins/'.$id.'.json','');
//            $data = $disk->get('coins/'.$id.'.json');
//        } catch (FileNotFoundException $e) {
//            return $e;
//        }
        if($temp != ''){
            $has = strstr($temp,date('Y-m-d',strtotime($mode)));
            if($has !== false) $temp = substr('{'.$has,14);
            $endDate = substr($temp,-12,10);
            if($endDate == date("Y-m-d",strtotime("-1 day"))) return '['.$temp.']';
        }
        return '['.$temp.']';
    }

    private function getCoinDataFromAPI($id,$start,$end,$index,$mode = 'd1'){
        $index++;
        if($index == 5) abort(500,'仮想通貨データの取得が失敗しました！');
        try{
            return file_get_contents('https://api.coincap.io/v2/assets/'.$id.'/history?interval='.$mode.'&start='.$start.'&end='.$end);
        }catch (ErrorException $e){
            return $this->getCoinDataFromAPI($id,$start,$end,$index);
        }
    }

    public function getStockInfo(){
        $url = 'https://finance.yahoo.co.jp/';
        $spider  = new Spider();
        $crawler = $spider->request('GET', $url);
        $titles = $crawler->filter('.ymuiHeaderBGDark2');
        $stock = $titles->first()->outerHtml().$crawler->filter('#stockrnk')->outerHtml();
        $fx = $crawler->filter('.chartArea')->outerHtml();
        $profile = $titles->eq(2)->outerHtml().$crawler->filter('#profile')->outerHtml();
        return json_encode(['stock'=>$stock,'fx'=>$fx,'profile'=>$profile]);
    }

    private function getFiscalYear($init){
        $fiscalYearArr = $this->getFiscalYearArr();
        if(!$init) $year=$_GET['year'];
        else {
            if(date('m')>$fiscalYearArr[1]) $year=date('Y');
            else $year=date('Y')-1;
        }
        return $year;
    }

    private function getFiscalPeriod($year){
        $fiscalYearArr = $this->getFiscalYearArr();
        return [$year.'-'.$fiscalYearArr[0].'-01',($year+1).'-'.$fiscalYearArr[1].'-'.RedisKey::DAYS_OF_FISCAL_YEAR_END];
    }

    private function countForEncryptedDateByMax($data,$field_name,$max){
        $count = 0;
        foreach ($data as $datum){
            if($datum->$field_name<=$max) $count++;
        }
        return $count;
    }

    private function countForEncryptedDateByPeriod($data,$field_name,$period){
        $count = 0;
        foreach ($data as $datum){
            if($datum->$field_name<=$period[1] && $datum->$field_name>=$period[0]) $count++;
        }
        return $count;
    }

    private function getClientAllianceStatistical($period){
        $client_alliance['sum'] = Client::where('our_role',2)->where('cooperation_start','<=',$period[1])->count('*');
        $client_alliance['add'] = Client::where('our_role',2)->whereBetween('cooperation_start',$period)->count('*');
        if($client_alliance['add'] == $client_alliance['sum']) $client_alliance['per_add']=RedisKey::STATISTICAL_START;
        else $client_alliance['per_add'] = round($client_alliance['add']*100/($client_alliance['sum'] - $client_alliance['add']),2);
        $client_alliance['high'] = Client::where('our_role',2)->where('cooperation_start','<=',$period[1])->where('priority',0)->count('*');
        if($client_alliance['sum']==0) $client_alliance['per_high']=RedisKey::STATISTICAL_EMPTY;
        else $client_alliance['per_high'] = round($client_alliance['high']*100/$client_alliance['sum'],2);
        return $client_alliance;
    }

    private function getClientCooperationStatistical($period){
        $client_cooperation['sum'] = Client::where('our_role',1)->where('cooperation_start','<=',$period[1])->count('*');
        $client_cooperation['add'] = Client::where('our_role',1)->whereBetween('cooperation_start',$period)->count('*');
        if($client_cooperation['sum']==$client_cooperation['add']) $client_cooperation['per_add']=RedisKey::STATISTICAL_START;
        else $client_cooperation['per_add'] = round($client_cooperation['add']*100/($client_cooperation['sum'] - $client_cooperation['add']),2);
        $client_cooperation['high'] = Client::where('our_role',1)->where('cooperation_start','<=',$period[1])->where('priority',0)->count('*');
        if($client_cooperation['sum']==0) $client_cooperation['per_high']=RedisKey::STATISTICAL_EMPTY;
        else $client_cooperation['per_high'] = round($client_cooperation['high']*100/$client_cooperation['sum'],2);
        return $client_cooperation;
    }

    private function getEmployeeStatistical($period){
        $employee['sum'] = $this->countForEncryptedDateByMax(EmployeeBase::select('date_hire')->get(),'date_hire',$period[1]);
        $employee['add'] = $this->countForEncryptedDateByPeriod(EmployeeBase::select('date_hire')->get(),'date_hire',$period);
        if($employee['sum'] == $employee['add']) $employee['per_add'] = RedisKey::STATISTICAL_START;
        else $employee['per_add'] = round($employee['add']*100/($employee['sum'] - $employee['add']),2);
        $employee['office'] = $this->countForEncryptedDateByMax(EmployeeBase::select('date_hire')->where('retire_type_id',1)->get(),'date_hire',$period[1]);
        $employee['leave'] = $this->countForEncryptedDateByMax(EmployeeBase::select('date_hire')->where('retire_type_id',2)->get(),'date_hire',$period[1]);
        $employee['retirement'] = $this->countForEncryptedDateByMax(EmployeeBase::select('date_hire')->where('retire_type_id',3)->get(),'date_hire',$period[1]);
        if($employee['sum']==0){
            $employee['per_office'] = RedisKey::STATISTICAL_EMPTY;
            $employee['per_leave'] = RedisKey::STATISTICAL_EMPTY;
            $employee['per_retirement'] = RedisKey::STATISTICAL_EMPTY;
        }else{
            $employee['per_office'] = round($employee['office']*100/$employee['sum'],2);
            $employee['per_leave'] = round($employee['leave']*100/$employee['sum'],2);
            $employee['per_retirement'] = round($employee['retirement']*100/$employee['sum'],2);
        }
        return $employee;
    }

    private function getAssetStatistical($period){
        $asset['sum'] = AssetInfo::where('delivery_date','<=',$period[1])->count('*');
        $asset['add'] = AssetInfo::whereBetween('delivery_date',$period)->count('*');
        if($asset['sum'] == $asset['add']) $asset['per_add'] = RedisKey::STATISTICAL_START;
        else $asset['per_add'] = round($asset['add']*100/($asset['sum'] - $asset['add']),2);
        $asset['available'] = AssetInfo::whereHas('AssetRentalLog',function ($query){
            $query->where('status',0);
        })->where('delivery_date','<=',$period[1])->count('*');
        $asset['loaning'] = AssetInfo::whereHas('AssetRentalLog',function ($query){
            $query->where('status',1);
        })->where('delivery_date','<=',$period[1])->count('*');
        $asset['repairing'] = AssetInfo::whereHas('AssetRentalLog',function ($query){
            $query->where('status',2);
        })->where('delivery_date','<=',$period[1])->count('*');
        $asset['discarded'] = AssetInfo::whereHas('AssetRentalLog',function ($query){
            $query->where('status',3);
        })->where('delivery_date','<=',$period[1])->count('*');
        if($asset['sum']==0){
            $asset['per_available'] = RedisKey::STATISTICAL_EMPTY;
            $asset['per_loaning'] = RedisKey::STATISTICAL_EMPTY;
            $asset['per_repairing'] = RedisKey::STATISTICAL_EMPTY;
            $asset['per_discarded'] = RedisKey::STATISTICAL_EMPTY;
        }else{
            $asset['per_available'] = round($asset['available']*100/$asset['sum'],2);
            $asset['per_loaning'] = round($asset['loaning']*100/$asset['sum'],2);
            $asset['per_repairing'] = round($asset['repairing']*100/$asset['sum'],2);
            $asset['per_discarded'] = round($asset['discarded']*100/$asset['sum'],2);
        }
        return $asset;
    }

    private function getMonthSalesStatistical($year){
        $monthArr = $this->getFiscalYearArr()[2];
        $monthSales['year']=$year.'年度';
        $xArr = [];
        $y1Arr = [];
        $y2Arr = [];
        foreach($monthArr as $index=>$month){
            if($month==RedisKey::YEAR_START) $year=$year+1;
            $xArr[$index] = $year.'年'.$month.'月';
            if(!($year.$month>date('Y').date('m'))){
                $yearMonth = $year.'-'.$month.'-';
                $period = [$yearMonth.'01',$yearMonth.date('t',mktime(0,0,0,$month,01,$year))];
                $this->calcSales($period,$index,$y1Arr,$y2Arr);
            }
        }
        $monthSales['x_arr']=$xArr;
        $monthSales['y1_arr']=$y1Arr;
        $monthSales['y2_arr']=$y2Arr;
        return $monthSales;
    }

    private function getTenYearsSalesStatistical($year){
        $fiscalYearArr = $this->getFiscalYearArr();
        $xArr = [];
        $y1Arr = [];
        $y2Arr = [];
        for($i=$year-9;$i<=$year;$i++){
            array_push($xArr,$i);
        }
        $tenYearsSales['x_arr']=$xArr;
        foreach($xArr as $index=>$x){
            $period = [$x.'-'.$fiscalYearArr[0].'-01',($x+1).'-'.$fiscalYearArr[1].'-'.RedisKey::DAYS_OF_FISCAL_YEAR_END];
            $this->calcSales($period,$index,$y1Arr,$y2Arr);
        }
        $tenYearsSales['y1_arr']=$y1Arr;
        $tenYearsSales['y2_arr']=$y2Arr;
        return $tenYearsSales;
    }

    private function calcSales($period,$index,&$y1Arr,&$y2Arr){
        $currency = AdminSetting::select('currency_symbol')->first()->currency_symbol;
        $deposits = AccountsInvoice::select('invoice_total')->where('our_position_type',AccountsInvoice::POSITION_IN)
            ->whereBetween('created_date',$period)->get();
        $payments = AccountsInvoice::select('invoice_total')->where('our_position_type',AccountsInvoice::POSITION_OUT)
            ->whereBetween('created_date',$period)->get();
        $y1Arr[$index]=0;
        foreach ($deposits as $deposit){
            $paid = $deposit->invoice_total;
            if($paid!=null) $y1Arr[$index]+=str_replace([$currency,','],'',$paid);
        }
        $y2Arr[$index]=0;
        foreach ($payments as $payment){
            $paid = $payment->invoice_total;
            if($paid!=null) $y2Arr[$index]+=str_replace([$currency,','],'',$paid);
        }
    }

    private function getNews(){
        $url = 'https://www.drtech.jp/';
        $spider  = new Spider(HttpClient::create(['verify_peer' => false]));
        $crawler = $spider->request('GET', $url);
        $node = $crawler->filter('.newsframe');
        return $node->html();
    }

    private function getDocs(){
        $url = 'https://www.drtech.jp/seed/';
        $spider  = new Spider(HttpClient::create(['verify_peer' => false]));
        $crawler = $spider->request('GET', $url);
        $node = $crawler->filter('.entry-content');
        $docs = '';
        $node->filter('p')->each(function ($p,$index) use (&$docs){
            if($index>1) $docs .= $p->outerHtml();
        });
        return $docs;
    }

    private function getNewsFromYahoo(){
        $url = 'https://news.yahoo.co.jp/topics/business';
        $spider  = new Spider();
        $crawler = $spider->request('GET', $url);
        $html = '';
        $index = 1;
        $crawler->filter('.newsFeed_list .newsFeed_item')->each(function ($node) use (&$html,&$index){
            if($index<=12){
                if(!$node->filter('[id^=ad]')->count()){
                    $html .= $node->outerHtml();
                    $index++;
                }
            }else return;
        });
        $html = '<ul class="newsFeed_list">'.$html.'</ul>';
        return $html;
    }
    private function getInternationalNewsFromYahoo(){
        $url = 'https://news.yahoo.co.jp/topics/world';
        $spider  = new Spider();
        $crawler = $spider->request('GET', $url);
        $html = '';
        $index = 1;
        $crawler->filter('.newsFeed_list .newsFeed_item')->each(function ($node) use (&$html,&$index){
            if($index<=12){
                if(!$node->filter('[id^=ad]')->count()){
                    $html .= $node->outerHtml();
                    $index++;
                }
            }else return;
        });
        $html = '<ul class="newsFeed_list">'.$html.'</ul>';
        return $html;
    }
    public function showInfo(){
        try {
            $url = 'https://www.lactes.jp/showInfo';
            $spider  = new Spider(HttpClient::create(['verify_peer' => false]));
            $crawler = $spider->request('GET', $url);
            $node = $crawler->filter('#showInfo');
            return $node->html();
        }catch (\Exception $e) {
            return '';
        }
    }
    private function getFiscalYearArr(){
        $fiscal_year_end = AdminSetting::first()->closing_month;
        $fiscal_year_start = "03";
        $month_arr=[];
        switch ($fiscal_year_end){
            case "01":
                $fiscal_year_start="02";
                $month_arr=['02','03','04','05','06','07','08','09','10','11','12','01'];
                break;
            case "02":
                $fiscal_year_start="03";
                $month_arr=['03','04','05','06','07','08','09','10','11','12','01','02'];
                break;
            case "03":
                $fiscal_year_start="04";
                $month_arr=['04','05','06','07','08','09','10','11','12','01','02','03'];
                break;
            case "04":
                $fiscal_year_start="05";
                $month_arr=['05','06','07','08','09','10','11','12','01','02','03','04'];
                break;
            case "05":
                $fiscal_year_start="06";
                $month_arr=['06','07','08','09','10','11','12','01','02','03','04','05'];
                break;
            case "06":
                $fiscal_year_start="07";
                $month_arr=['07','08','09','10','11','12','01','02','03','04','05','06'];
                break;
            case "07":
                $fiscal_year_start="08";
                $month_arr=['08','09','10','11','12','01','02','03','04','05','06','07'];
                break;
            case "08":
                $fiscal_year_start="09";
                $month_arr=['09','10','11','12','01','02','03','04','05','06','07','08'];
                break;
            case "09":
                $fiscal_year_start="10";
                $month_arr=['11','12','01','02','03','04','05','06','07','08','09','10'];
                break;
            case "10":
                $fiscal_year_start="11";
                $month_arr=['11','12','01','02','03','04','05','06','07','08','09','10'];
                break;
            case "11":
                $fiscal_year_start="12";
                $month_arr=['12','01','02','03','04','05','06','07','08','09','10','11'];
                break;
            case "12":
                $fiscal_year_start="01";
                $month_arr=['02','03','04','05','06','07','08','09','10','11','12','01'];
                break;
        }
        return [$fiscal_year_start,$fiscal_year_end,$month_arr];
    }
}
