@extends('layouts.app')

@section('content')
    <div class="has-text-centered">    
        <h1 class="title is-1" style="margin: 30px 0;">About</h1>

        <div class="has-text-centered">
            <article class="message is-info">
              <div class="message-header">
                <p>Info</p>
              </div>
              <div class="message-body">
                This page is under development. My intention is for this page to document <br> the project and the technologies involved. Both for my own and others benefit.
              </div>
            </article>
            <p class="" style="margin: 20px 0;">
                Find, review and keep track of companies you like by adding them to your watchlists. <br> 
                Easily create notifications and get notified when company data changes.
            </p>
            <div class="">
                <div class="" style="margin-top: 25px;">
                    <h3 class="title is-3">1. Review and analyze</h3>
                    <img src="/images/company_page.png" style="max-width: 600px;">
                </div>
                <div class="" style="margin-top: 25px;">
                    <h3 class="title is-3">2. Add to watchlist</h3>
                    <img src="/images/watchlist.png" style="max-width: 600px;">
                </div>
                <div class="" style="margin-top: 25px;">
                    <h3 class="title is-3">3. Set up notifications</h3>
                    <img src="/images/notifications.png" style="max-width: 600px;">
                </div>
            </div>
        </div>
    </div>

@endsection