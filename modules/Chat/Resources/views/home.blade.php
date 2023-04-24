@extends('theme::layouts.app')

@section('styles')
	<style type="text/css">
		/* width */
		::-webkit-scrollbar {
		  width: 5px;
		}

		/* Track */
		::-webkit-scrollbar-track {
		  background: #dae2e7;
		}

		/* Handle */
		::-webkit-scrollbar-thumb {
		  background: #dae2e7;
		}

		/* Handle on hover */
		::-webkit-scrollbar-thumb:hover {
		  background: #2196f3;
		}
		aside {
		  width: 40rem;
		}
		.green-text {
		  color: ##00a884;
		}
		.green-bg {
		  color: #00a884;
		}
		.green-border {
		  border-color: #00a884;
		}
		.main-header,
		.aside-header {
		  background-color: #ffffff;
		  border-bottom-width: 1px;
		}
		.main-footer {
		  background-color: #ffffff;
		  border-top-width: 1px; 
		}
		.search-bar,
		.message, .aside-messages {
		  background-color: #ffffff;
		}
		.search-bar input,
		.message-active {
		  padding-left: 30px;
		}
		.single-message {
		  background-color: #dae2e7;
		}
		.single-message.user {
		  background-color: #2196f3;
		}
		.incoming-svg {
		  color: #dae2e7;
		  transform: scaleX(-1);
		}
		.user-svg {
		  color: #2196f3;
		} 
		.main-messages {
		  background: #ffffff;
		}
		.chat-logged-user{
			max-width: 27%;
		}
		.text-xl {
			color: slategrey;
			font-weight: 600;
		}

		@media only screen and (max-width: 600px) {
			aside {
			    width: 5rem;
			}
			.chat-logged-user {
			    max-width: 100%;
			}
			.selected-chat-user-dp{
				width: 40px;
			}
			.footer-icon{
				width: 20px;
			}
			.message-input{
				padding-top: 0.25rem;
    			padding-bottom: 0.25rem;
			}
		}
 
		@media only screen and (min-width: 600px) {

		}
 
		@media only screen and (min-width: 768px) {

		}
 
		@media only screen and (min-width: 992px) {

		}
 
		@media only screen and (min-width: 1200px) {

		}

	</style>

@endsection
@section('content')
<div class="flex flex-col px-8 mx-auto my-6 lg:flex-row max-w-7xl xl:px-5">
	<template id="messageBlock">
	  <div class="message text-gray-300 px-4 py-3 border-b border-gray-700">
	    <div class="flex items-center relative">
	      <div class="w-1/6">
	        <img class="w-11 h-11 rounded-full" id="personHeadshot" src="">
	      </div>
	      <div class="w-5/6">
	        <div class="text-xl truncate w-32" id="personName">Josh Peters</div>
	        <div class="text-sm truncate" id="messagePreview"></div>
	      </div>
	      <span class="absolute right-0 top-0 text-xs mt-1">13:00</span>
	    </div>
	  </div>
	</template>
	<!-- Only optimized for viewing on desktop -->
	<div class="w-full bg-white flex border-t-8 green-border">
	  <aside class="overflow-y-auto border-r border-gray-800 relative block">
	    <div class="aside-header sticky top-0 right-0 left-0 z-40 text-gray-400">
	      <div class="flex items-center px-4 py-3">
	        <div class="flex-1">
	          <img class="w-11 h-11 chat-logged-user rounded-full" src="https://www.writersdigest.com/.image/ar_1:1%2Cc_fill%2Ccs_srgb%2Cfl_progressive%2Cq_auto:good%2Cw_1200/MTcxMDY5MzE5OTYyMzcyMDgx/image-placeholder-title.jpg">
	        </div>
	        <div class="flex-1 text-right hidden  md:block">
	           
	          <svg class="inline w-6 h-6 cursor-pointer" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
	            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
	          </svg>
	        </div>
	      </div>
	      <div class="search-bar px-4 py-2 w-full  hidden  md:block">
	        <form method="GET">
	          <div class="relative text-gray-600 focus-within:text-gray-200">
	            <span class="absolute inset-y-0 left-0 flex items-center pl-2">
	              <button type="submit" class="mt-3 focus:outline-none focus:shadow-outline">
	                <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-4 h-4 text-gray-300">
	                  <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
	                </svg>
	              </button>
	            </span>
	            <input type="search" name="q" class="w-full py-2 text-sm  bg-gray-600 rounded-full pl-10 focus:outline-none focus:bg-white focus:text-gray-900" placeholder="Search or start new chat" autocomplete="off">
	          </div>
	        </form>
	      </div>
	    </div>
	    <div id="mainMessages" class="aside-messages">
	    	<div class="message text-gray-300 px-4 py-3 border-b border-gray-700">
			    <div class="flex items-center relative">
			      <div class="w-1/6">
			        <img class="w-11 h-11 rounded-full" id="personHeadshot" src="https://randomuser.me/api/portraits/thumb/women/45.jpg">
			      </div>
			      <div class="w-5/6 pl-2">
			        <div class="text-xl hidden  md:block truncate w-35" id="personName">یلدا صدر</div>
			        <div class="text-sm truncate hidden  md:block truncate w-35" id="messagePreview">اسلام‌شهر گیلان 29978 yld.sdr@example.com</div>
			      </div>
			      <span class="absolute right-0 top-0 text-xs mt-1  hidden  md:block">13:00</span>
			    </div>
			</div>
			<div class="message text-gray-300 px-4 py-3 border-b border-gray-700">
			    <div class="flex items-center relative">
			      <div class="w-1/6">
			        <img class="w-11 h-11 rounded-full" id="personHeadshot" src="https://randomuser.me/api/portraits/thumb/men/10.jpg">
			      </div>
			      <div class="w-5/6 pl-2">
			        <div class="text-xl hidden  md:block truncate w-35" id="personName">Eren Köybaşı</div>
			        <div class="text-sm truncate hidden  md:block truncate w-35" id="messagePreview">Ağrı Bingöl 93000 eren.koybasi@example.com</div>
			      </div>
			      <span class="absolute right-0 top-0 text-xs mt-1  hidden  md:block">13:00</span>
			    </div>
			</div>
			 
	    </div>
	  </aside>
	  <main id="messageBody" class="w-full bg-whatsapp relative overflow-y-auto">
	    <div class="main-header z-40 sticky top-0 right-0 left-0 text-gray-400">
	      <div class="flex items-center px-4 py-3">
	        <div class="flex-2">
	          <div class="flex">
	            <div class="mr-2">
	              <img class="w-11 h-11 rounded-full selected-chat-user-dp" src="https://randomuser.me/api/portraits/thumb/men/10.jpg">
	            </div>
	            <div>
	              <p class="text-md font-bold truncate w-32">Josh Peters</p>
	              <p class="text-sm text-gray-400 truncate w-38">last seen today at 14:46</p>
	            </div>
	          </div>
	        </div>
	        <div class="flex-1 text-right"> 
	          <svg class="inline w-6 h-6 cursor-pointer" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
	            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
	          </svg>
	        </div>
	      </div>
	    </div>
	    <div class="main-messages block px-4 py-3">
	      
	      <div class="flex justify-end">
	        <div class="single-message rounded-tl-lg rounded-bl-lg text-gray-200 rounded-br-lg user mb-4 px-4 py-2">Hey! Thought I'd reach out to say how are you? 😊</div>
	        <span><svg class="user-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 8 13" width="8" height="13">
	            <path opacity=".13" d="M5.188 1H0v11.193l6.467-8.625C7.526 2.156 6.958 1 5.188 1z"></path>
	            <path fill="currentColor" d="M5.188 0H0v11.193l6.467-8.625C7.526 1.156 6.958 0 5.188 0z"></path>
	          </svg></span>
	      </div>
	      <div class="flex">
	        <span><svg class="incoming-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 8 13" width="8" height="13">
	            <path opacity=".13" d="M5.188 1H0v11.193l6.467-8.625C7.526 2.156 6.958 1 5.188 1z"></path>
	            <path fill="currentColor" d="M5.188 0H0v11.193l6.467-8.625C7.526 1.156 6.958 0 5.188 0z"></path>
	          </svg></span>
	        <div class="single-message rounded-tr-lg text-gray-200 rounded-bl-lg rounded-br-lg mb-4 px-4 py-2">Hey Pal! I'm doing good, how have you been? Cold at the moment aye!</div>
	      </div>
	       
	    </div>
	    <div class="main-footer sticky bottom-0 right-0 left-0 text-gray-400">
	      <div class="flex items-center px-4 py-1">
	        <div class="flex">
	          <svg class="inline w-6 h-6 -mt-1 cursor-pointer footer-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
	            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
	          </svg>
	          <svg class="inline w-6 h-6 ml-2 -mt-1 cursor-pointer footer-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
	            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
	          </svg>
	        </div>
	        <div class="flex-grow">
	          <div class="px-4 py-2 w-full">
	            <form method="GET">
	              <div class="relative text-gray-600 focus-within:text-gray-200">
	                <input type="search" name="q" class="message-input w-full py-3 text-sm  bg-gray-700 rounded-full pl-5 focus:outline-none focus:bg-white focus:text-gray-900" placeholder="Type a message" autocomplete="off">
	              </div>
	            </form>
	          </div>
	        </div>
	        <div class="flex-none text-right">
	          <svg class="inline w-6 h-6 -mt-1 cursor-pointer footer-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
	            <path fill-rule="evenodd" d="M7 4a3 3 0 016 0v4a3 3 0 11-6 0V4zm4 10.93A7.001 7.001 0 0017 8a1 1 0 10-2 0A5 5 0 015 8a1 1 0 00-2 0 7.001 7.001 0 006 6.93V17H6a1 1 0 100 2h8a1 1 0 100-2h-3v-2.07z" clip-rule="evenodd" />
	          </svg>
	        </div>
	      </div>
	    </div>
	  </main>
	  <div />
</div>	
	
@endsection

@section('javascript')
	@if (auth()->check())
    <script>
        let auth_user = @JSON(auth()->user());
    </script>
    @endif
	<script type="text/javascript">
		 
	</script>
@endsection