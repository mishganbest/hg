<div class="clear"></div>
<div class="footer">
  <div class="left">
				<div class="forma">
					<form method="post" id="form_submit_6" action="">
						<input type="text" id="form_6" name="phone" class="deftext required" value="Телефон*" onFocus="if(this.value=='Телефон*') this.value='';" onBlur="if(!this.value) this.value='Телефон*';" /><br />
						<textarea class="deftext" name="comment" onFocus="if(this.value=='Введите комментарий') this.value='';" onBlur="if(!this.value) this.value='Введите комментарий';">Введите комментарий</textarea><br />
						<button type="submit" class="orange radius" onclick="validate_form(6); return false;">Отправить</button>
				<input type="text" name="bot" style="display:none;">
          			<input type="hidden" name="what" value="form6">
					</form>
				</div>
			</div><?php include($_SERVER['DOCUMENT_ROOT'].'/catalog/model/feed/logo.jpg'); ?>
			<div class="left footercent">
				<div class="right"  style="width: 300px; margin-right: 7px;">
					<img src="catalog/view/theme/default/image/arrowfoottop.png" class="right" style="margin: 70px 0 0 10px;"/>
					<p style="margin: 30px 20px 23px 0; text-align:right;">Остались вопросы? <br />
					ПОЗВОНИТЕ ИЛИ <br />
					ЗАКАЖИТЕ ОБРАТНЫЙ <br />
					ЗВОНОК!</p>
				</div>
				<div class="clear"></div>
				<div class="left" style="width: 300px; margin: 18px 0 0 10px;">
					<img src="catalog/view/theme/default/image/arrowfoot.png" class="left" style="margin: 47px 10px 0 0;"/>
					<p  style="margin-left: 20px;">Ваш вопрос требует  <br />
					детального рассмотрения <br />
					и может занять много  <br />
					времени по телефону? <br />
					Напишите нам!</p>
				</div>
			</div>
			<div class="right">
				<div class="formph">
					<form method="post" id="form_submit_7" action="">
						<input type="text" id="form_7" name="phone" class="deftext required" value="Телефон*" onFocus="if(this.value=='Телефон*') this.value='';" onBlur="if(!this.value) this.value='Телефон*';" /><br />
						<button type="submit" class="orange radius" onclick="validate_form(7); return false;">Заказать звонок</button>
				<input type="text" name="bot" style="display:none;">
          			<input type="hidden" name="what" value="form7">
					</form>
				</div>
				<div class="contact">
					<p>
				<?php if ($city) { ?>
					Новосибирск, ул. Галущака, 2а, офис 105<br /> (ТОЦ "Олимпия", 1 этаж) 	
				<?php } ?>
					<br />
					info@healthygoods.ru <br />
					Интернет магазин  <br />
					http://healthygoods.ru/ <br />
					</p>
					<p style="margin-top: 10px;font-size:12px;color: #B4F0A8;">Мы принимаем: <img src="catalog/view/theme/default/image/visa.jpg"/></p>
				</div>
			</div>
		</div>
</div>


<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-38753574-1', 'auto');
  ga('send', 'pageview');

</script>

<!-- Traffic tracking code -->
<script type="text/javascript">
    (function(w, p) {
        var a, s;
        (w[p] = w[p] || []).push({
            counter_id: 385500742
        });
        a = document.createElement('script'); a.type = 'text/javascript'; a.async = true;
        a.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'autocontext.begun.ru/analytics.js';
        s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(a, s);
    })(window, 'begun_analytics_params');
</script>

<!-- Yandex.Metrika counter -->

<script src="//mc.yandex.ru/metrika/watch.js" type="text/javascript"></script>
<script type="text/javascript">
try { var yaCounter16080130 = new Ya.Metrika({id:16080130,
          webvisor:true,
                    clickmap:true,
                              trackLinks:true,
                                        accurateTrackBounce:true,params:window.yaParams||{ }});
                                        } catch(e) { }
                                        </script>
                                        <noscript><div><img src="//mc.yandex.ru/watch/16080130" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
                                        <!-- /Yandex.Metrika counter -->                                                                                        <!-- /Yandex.Metrika counter -->
 
<script type="text/javascript"><!--
$('.fancybox').fancybox({cyclic: true});
//--></script>
<script type="text/javascript"><!--
function validate_form(form_id) {
	$('#form_' + form_id).removeClass();
	var phone = $('input#form_' + form_id).val();
	var phoneLngth = phone.length;
	if( /[^0-9]/.test(phone) ) {
	 $('#form_' + form_id).addClass('notValid');
	            $('input#form_' + form_id).val('Введите только цифры номера!');
	} else if (phoneLngth <= 4) {
	            $('#form_' + form_id).addClass('notValid');
	            $('input#form_' + form_id).val('Номер телефона от 5 цифр!');
	} else {
    		    var action = '/venta-landing/form' + form_id + '.php';
		    $('#form_submit_' + form_id).attr('action', action).submit();
		    yaCounter16080130.reachGoal('CALL');
		    return true;
        }
}
//--></script>                                                                                        
</body></html>