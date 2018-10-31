{*
  *This template is call in Header and below code is use for add google tag manager in site.
*}

{if $page.page_name == 'index' and $referer == 'Redeal'}

    {literal}

        <script type="text/javascript">

            (function (i, s, o, g, r, a, m) {
                i['RedealObject'] = r;
                i[r] = i[r] || function () {
                    (i[r].q = i[r].q || []).push(arguments)
                },
                        i[r].l = 1 * new Date();
                a = s.createElement(o),
                        m = s.getElementsByTagName(o)[0];
                a.async = 1;
                a.src = g;
                m.parentNode.insertBefore(a, m)
            })
                    (window, document, 'script', window.location.protocol + '//widget.redeal.se/js/redeal.js', 'redeal');
        </script>

    {/literal}

{/if}


{if $page.page_name == 'order-confirmation'}
      {literal}
          <script type="text/javascript">
              redeal();
          </script>
      {/literal}
{/if}
