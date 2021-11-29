<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\AccountsInvoice;
use Goutte\Client as Spider;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;

class ShowInfoController extends Controller
{

    /**
     *
     */

    public function index(){
        $test1=AccountsInvoice::all();
//        return view('showInfo',$this->getStatistical());
        return view('showInfo',compact('test1'));
    }
    private function getStatistical(){

        $statistical['news']=$this->getNews();
        $statistical['docs']=$this->getDocs();
        return $statistical;
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
}
