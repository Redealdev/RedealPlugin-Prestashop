{*
*This template will call when order will be confirmed and below code is use for call redeal.js and display popup in order confirmation page.
*}


{if $page.page_name == 'order-confirmation'}
    {literal} 
        <script>
            (function (i, s, o, g, r, a, m) {
                i['RedealObject'] = r;
                i[r] = i[r] || function () {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
                a = s.createElement(o), m = s.getElementsByTagName(o)[0];
                a.async = 1;
                a.src = g;
                m.parentNode.insertBefore(a, m)
            })(window, document, 'script', window.location.protocol + '//widget.redeal.se/js/redeal.js', 'redeal');

            var redeal_data = {/literal}{$redealdata|unescape:'javascript' nofilter}{literal};

            redeal_data.event = "orders";
            window.dataLayer = window.dataLayer || [];

            dataLayer.push(redeal_data);

            redeal('checkout', redeal_data);
        </script>
    {/literal}

{/if}