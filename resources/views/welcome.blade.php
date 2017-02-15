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
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                    tempor incididunt ut labore et dolore magna aliqua.</p>
                </div>

                <div class="column">
                    <h3 class="title is-4" style="color: hsl(171, 100%, 41%);">Add to watchlsit</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                    tempor incididunt ut labore et dolore magna aliqua.</p>
                </div>

                <div class="column">
                    <h3 class="title is-4" style="color: hsl(171, 100%, 41%);">Set up notifications</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                    tempor incididunt ut labore et dolore magna aliqua.</p>
                </div>
            </div>
        </div>
    </div>

@endsection




