<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdyenPayByLink;
use Illuminate\Support\Facades\Cache;

class WebhooksController extends Controller
{
  public function __construct() {
  }

  public function webhookViewer(Request $request){
      return view('webhook-viewer', [
        'pusherId' => \Config::get('broadcasting.connections.pusher.app_id'),
        'pusherKey' => \Config::get('broadcasting.connections.pusher.key'),
        'pusherCluster' => \Config::get('broadcasting.connections.pusher.options.cluster'),
        ]);
  }

    public function handlePlatformNotification(Request $request) {
        $output = new \Symfony\Component\Console\Output\ConsoleOutput();
        $output->writeln('Notification Recieved');
        return '[accepted]';
    }

    public function handlePaymentNotification(){
        return '[accepted]';
    }
}
