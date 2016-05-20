<?php
  $libPath = base_path(). "/whoisxmlapi_4";

  $item_des = $_REQUEST['item_des'];
  $item_price = $_REQUEST['item_price'];
  $public_key = get_from_post_get('public_key');

  if (!$public_key)
    $public_key = 'pk_WcX2cQdbWunp0DSNAb1PbMCfEpFaY';//$STRIP_API_CURRENT_PUBLIC_KEY;
  
?>

@extends('layouts.master')

@section('title')
Payment
@stop

@section('content')
<div class="main-content">
  <div class="row wa-searchbox-radio">
    <div class="col-xs-12 wa-auto-margin">
      <div class="row">
        <div class="col-sm-6 col-xs-12 wa-search-eg-box wa-search-eg-box-customOrder">
          <form id="whoisform" name="whoisform" action="{{ URL::to('whoislookup.php') }}">
            <div class="form-group has-feedback wa-search-box">
              <input type="text" class="form-control wa-search wa-search-customOrder"  name="domainName" id="wa-search-whoislookup" placeholder="Whois Lookup">
              <span class="glyphicon glyphicon-search form-control-feedback wa-cursor" id="wa-search-icon-whoislookup"></span>
              <div class="wa-exapple wa-exapple-customOrder">Example: google.com or 74.125.45.100</div>
              <div class="wa-radio-inputs wa-radio-xml-Josn wa-radio-xml-Josn-customOrder">
                <div class="wa-radio-input wa-radio-input-xml wa-radio-input-xml-customOrder">
                  <input type="radio" value="xml" checked id="wa-radio-xml" class="wa-cursor wa-radio-orange-bar wa-field-input-selection wa-field-input-selection-customOrder wa-api-res-type" name="outputFormat">
                  <label for="wa-radio-xml" class="wa-cursor wa-field-value-selection wa-field-value-selection-xml wa-field-value-selection-customOrder" id="wa-lbl-XMl">XML</label>
                  <div class="wa-home-radio-outerCircle">
                    <div class="wa-home-radio-innerCircle"></div>
                  </div>
                </div>
                <div class="wa-radio-input wa-radio-input-json wa-radio-input-json-customOrder">
                  <input type="radio" value="json" id="wa-radio-json" class="wa-cursor wa-radio-orange-bar wa-field-input-selection wa-field-input-selection-customOrder wa-api-res-type" name="outputFormat">
                  <label for="wa-radio-json" class="wa-cursor wa-field-value-selection wa-field-value-selection-json wa-field-value-selection-customOrder" id="wa-lbl-JSON">JSON</label>
                  <div class="wa-home-radio-outerCircle">
                    <div class="wa-home-radio-innerCircle" style="display: none;"></div>
                  </div>
                </div>
              </div>
              <div id="wa-user-stats" class="wa-user-stats"><?php include_once $libPath . "/user_stats.php"; ?>
              </div>
            </div>
          </form>
        </div>
        <div class="col-sm-6 col-xs-12 wa-btn">
          <div class="row">
            <div class="col-sm-offset-4 col-sm-4 col-xs-12 wa-btn-order">
              <a href={{ URL::to('order_paypal.php') }}><button type="button" class="btn btn-default wa-btn wa-btn-orderNow wa-btn-orderNow-customOrder center-block" id="wa-btn-home-orderNow">ORDER NOW</button></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row wa-page-title-content-bg">
    <div class="col-xs-12 wa-about-whoisApi wa-auto-margin wa-col-xs-no-padding">
      <h1 class="text-center wa-title wa-title-customOrder wa-title-secureorder-customOrder">Payment</h1>
    </div>
  </div>
  <div id="wa-page-content">
    <div class="row">
      <div class="col-xs-12 wa-auto-margin">
        <div class="wa-box wa-box-xs-padding wa-box-customizedOrder">
          {{ HTML::image('images/paypal2.png', 'Responsive image', array('class'=>'img-responsive')) }}
          
          <form action="{{ $data['form_action'] }}" method="POST" id="payment-form" class="form-horizontal">
            <input type="hidden" name="public_key" id="public_key" value="{{ $public_key }}"/>
            @if (Input::has('plan_id'))
              <input type="hidden" name="plan_id" id="plan_id" value="{{ $plan_id }}"/>
            @endif
            <div class="row row-centered">
              <div class="col-md-8 col-md-offset-2">

                <noscript>
                <div class="bs-callout bs-callout-danger">
                  <h4>JavaScript is not enabled!</h4>
                  <p>This payment form requires your browser to have JavaScript enabled. Please activate JavaScript and reload this page. Check <a href="http://enable-javascript.com" target="_blank">enable-javascript.com</a> for more informations.</p>
                </div>
                </noscript>

                <div class="alert alert-danger" id="a_x200" style="display: none;"> <strong>Error!</strong> <span class="payment-errors"></span> </div>
            
                <fieldset>
                  <div class="form-group">
                    <label class="col-sm-4 control-label" for="textinput">Card Number</label>
                    <div class="col-sm-6">
                      <input type="text" name="card_number" id="card_number" maxlength="19" class="card-number form-control">
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label class="col-sm-4 control-label" for="textinput">CVC</label>
                    <div class="col-sm-3">
                      <input type="text" name="cvv" id="cvv" maxlength="4" class="card-cvc form-control">
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-4 control-label" for="textinput">Expiration (MM/YYYY)</label>
                    <div class="col-sm-6">
                      <div class="form-inline">
                        <select name="select2" data-stripe="exp-month" class="card-expiry-month stripe-sensitive required form-control">
                          <option value="01" selected="selected">01</option>
                          <option value="02">02</option>
                          <option value="03">03</option>
                          <option value="04">04</option>
                          <option value="05">05</option>
                          <option value="06">06</option>
                          <option value="07">07</option>
                          <option value="08">08</option>
                          <option value="09">09</option>
                          <option value="10">10</option>
                          <option value="11">11</option>
                          <option value="12">12</option>
                        </select>
                        <span> / </span>
                        <select name="select2" data-stripe="exp-year" class="card-expiry-year stripe-sensitive required form-control">
                        </select>
                        <script type="text/javascript">
                          var select = $(".card-expiry-year"),
                          year = new Date().getFullYear();
               
                          for (var i = 0; i < 12; i++) {
                              select.append($("<option value='"+(i + year)+"' "+(i === 0 ? "selected" : "")+">"+(i + year)+"</option>"))
                          }
                      </script> 
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-4 control-label"  for="textinput">Email (where payment confirmation will be sent)</label>
                    <div class="col-sm-6">
                      <input type="text" name="customer_email" id="customer_email" maxlength="70" class="card-holder-name form-control">
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-4 control-label" for="textinput">Item description</label>
                    <div class="col-sm-6">
                      <input type="text" id="item_des" name="item_des" class="form-control" value="{{ $item_des }}"/>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-4 control-label" for="textinput">Item Price</label>
                    <div class="col-sm-6">
                      <input type="text" id="item_price" name="item_price" class="form-control" value="{{ $item_price }}"/>
                    </div>
                  </div>
                  
                  <!-- Submit -->
                  <div class="control-group">
                    <div class="controls">
                      <center>
                        <button class="btn btn-success" type="submit">Next</button>
                      </center>
                    </div>
                  </div>
                </fieldset>
              </div>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>
@stop


@section('scripts')
@parent
<script type="text/javascript" src="https://js.stripe.com/v2"></script>
{{ HTML::script('js/bootstrapValidator-min.js') }}

<script type="text/javascript">

  // this identifies your website in the createToken call below
  Stripe.setPublishableKey('<?php echo $public_key;?>');
     
  $(document).ready(function() {	
	
    $('#payment-form').bootstrapValidator({
        message: 'This value is not valid',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        submitHandler: function(validator, form, submitButton) {
          
          // createToken returns immediately - the supplied callback submits the form if there are no errors
          Stripe.card.createToken({
            number: $('.card-number').val(),
            cvc: $('.card-cvc').val(),
            exp_month: $('.card-expiry-month').val(),
            exp_year: $('.card-expiry-year').val()
          }, stripeResponseHandler);
            return false; // submit from callback
        },
        fields: {
          customer_email: {
              validators: {
                  notEmpty: {
                      message: 'The email is required and can\'t be empty'
                  },
                  email: true
              }
          },
          card_number: {
            selector: '#card_number',
            validators: {
              notEmpty: {
                message: 'The credit card number is required and can\'t be empty'
              },
              creditCard: {
                message: 'The credit card number is invalid'
              },
            }
          },
          expMonth: {
            selector: '[data-stripe="exp-month"]',
            validators: {
              notEmpty: {
                  message: 'The expiration month is required'
              },
              digits: {
                  message: 'The expiration month can contain digits only'
              },
              callback: {
                  message: 'Expired',
                  callback: function(value, validator) {
                      value = parseInt(value, 10);
                      var year         = validator.getFieldElements('expYear').val(),
                          currentMonth = new Date().getMonth() + 1,
                          currentYear  = new Date().getFullYear();
                      if (value < 0 || value > 12) {
                          return false;
                      }
                      if (year == '') {
                          return true;
                      }
                      year = parseInt(year, 10);
                      if (year > currentYear || (year == currentYear && value > currentMonth)) {
                          validator.updateStatus('expYear', 'VALID');
                          return true;
                      } else {
                          return false;
                      }
                  }
              }
            }
          },
          expYear: {
              selector: '[data-stripe="exp-year"]',
              validators: {
                notEmpty: {
                    message: 'The expiration year is required'
                },
                digits: {
                    message: 'The expiration year can contain digits only'
                },
                callback: {
                  message: 'Expired',
                  callback: function(value, validator) {
                    value = parseInt(value, 10);
                    var month        = validator.getFieldElements('expMonth').val(),
                        currentMonth = new Date().getMonth() + 1,
                        currentYear  = new Date().getFullYear();
                    if (value < currentYear || value > currentYear + 100) {
                        return false;
                    }
                    if (month == '') {
                        return false;
                    }
                    month = parseInt(month, 10);
                    if (value > currentYear || (value == currentYear && month > currentMonth)) {
                        validator.updateStatus('expMonth', 'VALID');
                        return true;
                    } else {
                        return false;
                    }
                }
              }
            }
          },
          cvv: {
            selector: '#cvv',
            validators: {
              notEmpty: {
                message: 'The cvv is required and can\'t be empty'
              },
              cvv: {
                message: 'The value is not a valid CVV',
                creditCardField: 'card_number'
              }
            }
          }
        }
    });

  });

  function stripeResponseHandler(status, response) {
    if (response.error) {
      // re-enable the submit button
      $('.submit-button').removeAttr("disabled");
      // show hidden div
      document.getElementById('a_x200').style.display = 'block';
          // show the errors on the form
          $(".payment-errors").html(response.error.message);
      } else {
          var form$ = $("#payment-form");
          // token contains id, last4, and card type
          var token = response['id'];
          // insert the token into the form so it gets submitted to the server
          form$.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
          // and submit
          form$.get(0).submit();
      }
  }

  function isValidEmailAddress(emailAddress) 
  {
      var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
      return pattern.test(emailAddress);
  };

</script>

@stop