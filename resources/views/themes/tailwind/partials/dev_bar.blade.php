<div
    x-init="$watch('open', value => {
        if(value){
            document.body.classList.add('overflow-hidden');
            let thisElement = $el;
        } else {
            document.body.classList.remove('overflow-hidden');
        }})"
    id="workspace_dev_bar"
    class="fixed bottom-0 left-0 z-40 w-full h-screen transition-all duration-150 ease-out transform"
    x-data="{ open: false, url: '', active: '' }"
    :class="{ 'translate-y-full': !open, 'translate-y-0': open }"
    x-on:keydown.escape.window="open = false"
    x-cloak>
    <div class="fixed inset-0 z-20 bg-black bg-opacity-25" x-show="open" @click="open=false"></div>



    <div class="absolute inset-0 z-30 hidden sm:block" :class="{ 'bottom-0': !open }">

        <div class="inset-0 z-40 transition duration-200 ease-out" :class="{ 'absolute h-14': open, 'relative h-10 -mt-10': !open }">
            <div class="w-full h-full border-t border-blue-500 bg-gradient-to-r from-workspace-500 via-blue-500 to-purple-600" :class="{ 'overflow-hidden': open }">
                <div class="flex justify-between w-full h-full">
                    <div class="flex h-full">
                        <div class="relative flex items-center justify-center h-full" :class="{ 'px-2': !open, 'px-4': open }">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="35px" height="35px" viewBox="0 0 51 51" version="1.1">
                                <g id="surface1">
                                <path style="stroke:none;fill-rule:nonzero;fill: #f3f4f6;fill-opacity:1;" d="M 44.027344 1.632812 C 42.234375 2.667969 42.15625 2.789062 43.152344 2.789062 C 43.75 2.789062 44.226562 2.910156 44.226562 3.027344 C 44.226562 3.148438 42.992188 5.855469 41.4375 9.042969 L 38.648438 14.820312 L 37.492188 13.824219 C 28.410156 6.214844 14.34375 8.246094 7.769531 18.167969 C 5.378906 21.753906 4.382812 25.023438 4.382812 29.523438 C 4.382812 35.582031 6.296875 40.5625 10.199219 44.464844 C 14.222656 48.488281 18.886719 50.402344 24.703125 50.402344 C 30.601562 50.402344 35.222656 48.449219 39.285156 44.347656 C 41.597656 41.996094 44.066406 37.492188 44.503906 34.941406 L 44.746094 33.46875 L 23.109375 33.46875 L 23.109375 36.65625 L 39.882812 36.65625 L 38.808594 38.648438 C 36.535156 42.832031 31.714844 46.21875 26.933594 47.015625 C 16.296875 48.730469 6.652344 38.609375 8.367188 27.492188 C 9.285156 21.59375 13.625 15.859375 18.605469 13.90625 C 21.394531 12.828125 25.976562 12.472656 28.609375 13.148438 C 30.558594 13.625 34.066406 15.460938 33.746094 15.816406 C 33.628906 15.9375 31.636719 16.414062 29.285156 16.933594 C 26.972656 17.410156 25.023438 17.890625 24.941406 17.96875 C 24.902344 18.050781 21.914062 23.070312 18.367188 29.046875 L 11.914062 40.003906 L 13.546875 41.597656 C 14.941406 42.992188 15.21875 43.152344 15.660156 42.632812 C 15.976562 42.3125 18.765625 37.375 21.914062 31.675781 C 25.023438 25.976562 27.613281 21.277344 27.652344 21.238281 C 27.730469 21.15625 29.722656 20.519531 32.113281 19.761719 C 35.101562 18.847656 36.617188 18.566406 36.976562 18.847656 C 37.652344 19.363281 37.574219 19.921875 36.816406 19.921875 C 36.496094 19.921875 37.613281 20.640625 39.285156 21.515625 C 41 22.390625 42.433594 23.03125 42.511719 22.988281 C 42.59375 22.910156 42.511719 21.15625 42.355469 19.085938 C 42.113281 15.699219 42.035156 15.460938 41.636719 16.496094 C 41.359375 17.132812 41.078125 17.492188 40.960938 17.292969 C 40.839844 17.09375 41.835938 13.90625 43.152344 10.238281 C 45.542969 3.546875 45.582031 3.507812 46.296875 4.460938 L 47.015625 5.378906 L 47.015625 4.382812 C 47.015625 2.867188 46.539062 0.398438 46.257812 0.4375 C 46.140625 0.476562 45.144531 0.996094 44.027344 1.632812 Z M 44.027344 1.632812 "></path>
                                <path style="stroke:none;fill-rule:nonzero;fill: #f3f4f6;fill-opacity:1;" d="M 29.804688 21.277344 C 29.164062 21.476562 28.6875 21.875 28.6875 22.074219 C 28.648438 22.3125 29.085938 24.78125 29.5625 27.570312 L 30.441406 32.671875 L 32.59375 32.671875 C 34.425781 32.671875 34.703125 32.59375 34.503906 31.953125 C 34.386719 31.597656 33.628906 29.125 32.832031 26.496094 C 32.035156 23.867188 31.277344 21.515625 31.15625 21.277344 C 30.957031 20.996094 30.519531 20.996094 29.804688 21.277344 Z M 29.804688 21.277344 "></path>
                                </g>
                            </svg>
                        </div>

                        <div @click="open=true; url='/docs'; active='docs';" class="flex items-center justify-center h-full text-xs leading-none text-blue-100 border-l border-blue-500 cursor-pointer hover:bg-workspace-600" :class="{ 'px-3': !open, 'px-5': open, 'bg-workspace-600': active == 'docs' && open, 'bg-workspace-500': active != 'docs' }">
                            Documentation
                        </div>
                        @if(!auth()->guest() && auth()->user()->can('browse_admin'))
                            <div @click="open=true; url='/admin'; active='admin';" class="flex items-center justify-center h-full text-xs leading-none text-blue-100 border-l border-blue-500 cursor-pointer hover:bg-workspace-600" :class="{ 'px-3': !open, 'px-5': open, 'bg-workspace-600': active == 'admin' && open, 'bg-workspace-500': active != 'admin' }">
                                Admin
                            </div>
                        @endif
                    </div>
                    <div x-show="open" @click="open=false" class="flex flex-col items-center justify-center h-full text-white border-l border-purple-500 opacity-75 cursor-pointer w-14 hover:bg-black hover:bg-opacity-10 hover:opacity-100">

                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        <span class="text-xs opacity-50">esc</span>
                    </div>
                </div>
            </div>
        </div>


        <div class="relative w-full h-full overflow-hidden bg-white">
            <iframe class="w-full h-full pt-14" :src="url"></iframe>
        </div>
    </div>
</div>
