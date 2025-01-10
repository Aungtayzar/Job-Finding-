@props(['mobile'=>null])
<form action="{{route('logout')}}" method="POST" class="py-2">
    @csrf
    <button type="submit" class="text-white {{$mobile ? 'px-4' : ''}} ">
        <i class="fa fa-sign-out"></i> Logout
    </button>
</form>