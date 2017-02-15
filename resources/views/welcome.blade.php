@extends('layouts.app')

@section('content')
    <div style="margin-top: 70px;">
        <div style="">
            <div class="columns" style="margin-bottom: 70px;">
                <div class="column">
                    <img class="is-pulled-right" src="/images/logo.png" style="width: 200px; margin-right: 200px;">        
                </div>
                <div class="column" style="margin-left: -150px;">
                    <h2 class="title is-1" style="margin: 15px 0; font-family: Lato:100;">Welcome to<br>Company Watchlist</h2>
                    
                    <a class="button is-large is-primary" style="margin-right: 50px;" href="https://github.com/DidrikF/watchlist">
                        <span class="icon is-medium">
                          <i class="fa fa-github"></i>
                        </span>
                        <span>GitHub</span>
                      </a>
                    <a class="button is-large" href="{{ env('APP_URL') }}/about">
                        <span class="icon is-medium">
                          <i class="fa fa-book" aria-hidden="true"></i>
                        </span>
                        <span>About</span>
                      </a>
                </div>
                
            </div>
            <div class="has-text-centered columns">
                <div class="column">
                    <h3 class="title is-4" style="color: hsl(171, 100%, 41%);">Review and analyze</h3>
                    <p>Search for a company you are interested in. On the company's page you find valuable financial data. After reviewing the company you can capure your thoughts by filling in a simple analysis form.</p>
                </div>

                <div class="column">
                    <h3 class="title is-4" style="color: hsl(171, 100%, 41%);">Create watchlists</h3>
                    <p>When you find a company you would like to keep an eye on in the future, you can add it to a watchlist. You can create as many watchlists as you want. For instance, you can make one for each industry you are following.</p>
                </div>

                <div class="column">
                    <h3 class="title is-4" style="color: hsl(171, 100%, 41%);">Set up notifications</h3>
                    <p>It's hard to keep track of market developments. We enable you to set up custom notifications. A notification consists of a set of conditions you define. When the conditions are met, you are made aware through email and the web GUI.</p>
                </div>
            </div>
        </div>
    </div>

@endsection




