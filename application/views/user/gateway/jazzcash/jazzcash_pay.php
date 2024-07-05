                                        <script>
                                            function submitForm() {

                                                CalculateHash();
                                                var IntegritySalt = document.getElementById("salt").innerText;
                                                var hash = CryptoJS.HmacSHA256(document.getElementById("hashValuesString").value, IntegritySalt);
                                                document.getElementsByName("pp_SecureHash")[0].value = hash + '';

                                                console.log('string: ' + hashString);
                                                console.log('hash: ' + document.getElementsByName("pp_SecureHash")[0].value);

                                                document.jsform.submit();
                                            }
                                        </script>
                                        <script src="https://sandbox.jazzcash.com.pk/Sandbox/Scripts/hmac-sha256.js"></script>

                                        <h3><?php echo $this->lang->line('loading'); ?></h3>
                                        <div class="jsformWrapper">
                                            <form name="jsform" method="post" action="https://sandbox.jazzcash.com.pk/CustomerPortal/transactionmanagement/merchantform/">
                                                <?php 
                                                foreach ($payment_data as $key => $value) {
                                                   ?>
                                                   <input type="hidden" name="<?php echo $key;?>" id="<?php echo $key; ?>" value="<?php echo $value; ?>">
                                                   <?php
                                                }
                                                ?>
                                               

                                            </form>

                                            <label id="salt" style="display:none;"></label>
                                            <br><br>
                                            <div class="formFielWrapper" style="margin-bottom: 2rem;">
                                                <label class="active"></label>
                                                <input type="hidden" id="hashValuesString" value="">
                                                <br><br>
                                            </div>

                                        </div>

                                        <script>
                                            submitForm();
                                            function CalculateHash() {
                                                var IntegritySalt = document.getElementById("salt").innerText;
                                                hashString = '';

                                                hashString += IntegritySalt + '&';

                                                if (document.getElementsByName("pp_Amount")[0].value != '') {
                                                    hashString += document.getElementsByName("pp_Amount")[0].value + '&';
                                                }

                                                if (document.getElementsByName("pp_BillReference")[0].value != '') {
                                                    hashString += document.getElementsByName("pp_BillReference")[0].value + '&';
                                                }
                                                if (document.getElementsByName("pp_CustomerEmail")[0].value != '') {
                                                    hashString += document.getElementsByName("pp_CustomerEmail")[0].value + '&';
                                                }
                                                if (document.getElementsByName("pp_CustomerID")[0].value != '') {
                                                    hashString += document.getElementsByName("pp_CustomerID")[0].value + '&';
                                                }
                                                if (document.getElementsByName("pp_CustomerMobile")[0].value != '') {
                                                    hashString += document.getElementsByName("pp_CustomerMobile")[0].value + '&';
                                                }
                                                if (document.getElementsByName("pp_Description")[0].value != '') {
                                                    hashString += document.getElementsByName("pp_Description")[0].value + '&';
                                                }
                                                if (document.getElementsByName("pp_IsRegisteredCustomer")[0].value != '') {
                                                    hashString += document.getElementsByName("pp_IsRegisteredCustomer")[0].value + '&';
                                                }
                                                if (document.getElementsByName("pp_Language")[0].value != '') {
                                                    hashString += document.getElementsByName("pp_Language")[0].value + '&';
                                                }
                                                if (document.getElementsByName("pp_MerchantID")[0].value != '') {
                                                    hashString += document.getElementsByName("pp_MerchantID")[0].value + '&';
                                                }
                                                if (document.getElementsByName("pp_Password")[0].value != '') {
                                                    hashString += document.getElementsByName("pp_Password")[0].value + '&';
                                                }
                                                if (document.getElementsByName("pp_ReturnURL")[0].value != '') {
                                                    hashString += document.getElementsByName("pp_ReturnURL")[0].value + '&';
                                                }
                                                if (document.getElementsByName("pp_SubMerchantID")[0].value != '') {
                                                    hashString += document.getElementsByName("pp_SubMerchantID")[0].value + '&';
                                                }
                                                if (document.getElementsByName("pp_TokenizedCardNumber")[0].value != '') {
                                                    hashString += document.getElementsByName("pp_TokenizedCardNumber")[0].value + '&';
                                                }
                                                if (document.getElementsByName("pp_TxnCurrency")[0].value != '') {
                                                    hashString += document.getElementsByName("pp_TxnCurrency")[0].value + '&';
                                                }
                                                if (document.getElementsByName("pp_TxnDateTime")[0].value != '') {
                                                    hashString += document.getElementsByName("pp_TxnDateTime")[0].value + '&';
                                                }
                                                if (document.getElementsByName("pp_TxnExpiryDateTime")[0].value != '') {
                                                    hashString += document.getElementsByName("pp_TxnExpiryDateTime")[0].value + '&';
                                                }
                                                if (document.getElementsByName("pp_TxnRefNo")[0].value != '') {
                                                    hashString += document.getElementsByName("pp_TxnRefNo")[0].value + '&';
                                                }

                                                if (document.getElementsByName("pp_TxnType")[0].value != '') {
                                                    hashString += document.getElementsByName("pp_TxnType")[0].value + '&';
                                                }

                                                if (document.getElementsByName("pp_Version")[0].value != '') {
                                                    hashString += document.getElementsByName("pp_Version")[0].value + '&';
                                                }
                                                if (document.getElementsByName("ppmpf_1")[0].value != '') {
                                                    hashString += document.getElementsByName("ppmpf_1")[0].value + '&';
                                                }
                                                if (document.getElementsByName("ppmpf_2")[0].value != '') {
                                                    hashString += document.getElementsByName("ppmpf_2")[0].value + '&';
                                                }
                                                if (document.getElementsByName("ppmpf_3")[0].value != '') {
                                                    hashString += document.getElementsByName("ppmpf_3")[0].value + '&';
                                                }
                                                if (document.getElementsByName("ppmpf_4")[0].value != '') {
                                                    hashString += document.getElementsByName("ppmpf_4")[0].value + '&';
                                                }
                                                if (document.getElementsByName("ppmpf_5")[0].value != '') {
                                                    hashString += document.getElementsByName("ppmpf_5")[0].value + '&';
                                                }

                                                hashString = hashString.slice(0, -1);
                                                document.getElementById("hashValuesString").value = hashString;
                                            }
                                        </script>
