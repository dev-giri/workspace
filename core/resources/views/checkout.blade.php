<script src="https://cdn.paddle.com/paddle/paddle.js"></script>
<script>

    window.vendor_id = parseInt('{{ config("workspace.paddle.vendor") }}');

    if(vendor_id){
        Paddle.Setup({ vendor: vendor_id });
    }

    if("{{ config('workspace.paddle.env') }}" == 'sandbox') {
        Paddle.Environment.set('sandbox');
    }

    let checkoutBtns = document.getElementsByClassName("checkout");
    for( var i=0; i < checkoutBtns.length; i++ ){
        checkoutBtns[i].addEventListener('click', function(){
            workspaceCheckout(this.dataset.plan)
        }, false);
    }

    let updateBtns = document.getElementsByClassName("checkout-update");
    for( var i=0; i < updateBtns.length; i++ ){
        updateBtns[i].addEventListener('click', workspaceUpdate, false);
    }

    let cancelBtns = document.getElementsByClassName("checkout-cancel");
    for( var i=0; i < cancelBtns.length; i++ ){
        cancelBtns[i].addEventListener('click', workspaceCancel, false);
    }


    function workspaceCheckout(plan_id) {
        if(vendor_id){
            let product = parseInt(plan_id);
            Paddle.Checkout.open({
                product: product,
                email: '@if(!auth()->guest()){{ auth()->user()->email }}@endif',
                successCallback: "checkoutComplete",
            });
        } else {
            alert('Paddle Vendor ID is not set, please see the docs and learn how to setup billing.');
        }
    }

    function workspaceUpdate(){
        Paddle.Checkout.open({
            override: this.dataset.url,
            successCallback: "checkoutUpdate",
        });
    }

    function workspaceCancel(){
        Paddle.Checkout.open({
            override: this.dataset.url,
            successCallback: "checkoutCancel",
        });
    }

</script>
