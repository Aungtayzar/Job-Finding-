@props(['type','message','time'=>3000])


<div x-data="{show : true}" x-init="setTimeout(()=> show = false , {{$time}})" class="p-4 mb-4 text-sm text-white rounded {{$type == 'success' ? 'bg-green-500' : 'bg-red-500'}}" x-show="show">
    {{$message}}
</div>



