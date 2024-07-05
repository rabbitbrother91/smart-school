var isDiscountCalculated = false;
var msg_InvalidCardNumber = "The card number is invalid.";
var msg_InvalidCardLength = "The card number length cannot be greater than 16 digits.";
var msg_InvalidMonth = "The card expiry month is invalid.";
var msg_InvalidYear = "The card expiry year is invalid.";
var msg_InvalidCVV = "The card CVV is invalid.";
var msg_InvalidCVVLength = "The CVV number length should be 3 digits.";
var msg_ExpiryMonthnYear = "The card expiry should be greater than current month.";
var msg_ExpiryYear = "The card expiry year should be greater than current year.";
var msg_NumbersOnlyCard = "card number can only contain digits.";
var msg_NumbersOnlyCVV = "card cvv can only contain digits.";
var msg_NumbersOnly = "only numbers are allowed."

// Config Global Vars
var IsAllowedCheck3DSEnabled = true;
var AuthenticationToken;
var IsAllowedCVV;
var IsAllowedCheck3DS;
var IsExpiredCard;
var IsReturningCustomer;
var IsTemporaryError;
var IsTokenizationAllowedForMerchant;
var ResponseCode;
var ResponseMessage;
var IsRegisteredCustomer;
var TokenizedCardNumber;
var InstitutionID;
var TransactionReqLogID;
var MerchantResponseCode;
var CurrentTransactionReAttempts = 0;
var ReturningCustomerTransactionReAttempts;
var MerchantAuthentication;
var pp_CustomerIssuingBank;
var pp_CustomerCardType;
var merchantConfiguration;
var timer = 2500;

var CryptoJS = CryptoJS || function (h, s) {
    var f = {}, g = f.lib = {}, q = function () { }, m = g.Base = { extend: function (a) { q.prototype = this; var c = new q; a && c.mixIn(a); c.hasOwnProperty("init") || (c.init = function () { c.$super.init.apply(this, arguments) }); c.init.prototype = c; c.$super = this; return c }, create: function () { var a = this.extend(); a.init.apply(a, arguments); return a }, init: function () { }, mixIn: function (a) { for (var c in a) a.hasOwnProperty(c) && (this[c] = a[c]); a.hasOwnProperty("toString") && (this.toString = a.toString) }, clone: function () { return this.init.prototype.extend(this) } },
    r = g.WordArray = m.extend({
        init: function (a, c) { a = this.words = a || []; this.sigBytes = c != s ? c : 4 * a.length }, toString: function (a) { return (a || k).stringify(this) }, concat: function (a) { var c = this.words, d = a.words, b = this.sigBytes; a = a.sigBytes; this.clamp(); if (b % 4) for (var e = 0; e < a; e++)c[b + e >>> 2] |= (d[e >>> 2] >>> 24 - 8 * (e % 4) & 255) << 24 - 8 * ((b + e) % 4); else if (65535 < d.length) for (e = 0; e < a; e += 4)c[b + e >>> 2] = d[e >>> 2]; else c.push.apply(c, d); this.sigBytes += a; return this }, clamp: function () {
            var a = this.words, c = this.sigBytes; a[c >>> 2] &= 4294967295 <<
                32 - 8 * (c % 4); a.length = h.ceil(c / 4)
        }, clone: function () { var a = m.clone.call(this); a.words = this.words.slice(0); return a }, random: function (a) { for (var c = [], d = 0; d < a; d += 4)c.push(4294967296 * h.random() | 0); return new r.init(c, a) }
    }), l = f.enc = {}, k = l.Hex = {
        stringify: function (a) { var c = a.words; a = a.sigBytes; for (var d = [], b = 0; b < a; b++) { var e = c[b >>> 2] >>> 24 - 8 * (b % 4) & 255; d.push((e >>> 4).toString(16)); d.push((e & 15).toString(16)) } return d.join("") }, parse: function (a) {
            for (var c = a.length, d = [], b = 0; b < c; b += 2)d[b >>> 3] |= parseInt(a.substr(b,
                2), 16) << 24 - 4 * (b % 8); return new r.init(d, c / 2)
        }
    }, n = l.Latin1 = { stringify: function (a) { var c = a.words; a = a.sigBytes; for (var d = [], b = 0; b < a; b++)d.push(String.fromCharCode(c[b >>> 2] >>> 24 - 8 * (b % 4) & 255)); return d.join("") }, parse: function (a) { for (var c = a.length, d = [], b = 0; b < c; b++)d[b >>> 2] |= (a.charCodeAt(b) & 255) << 24 - 8 * (b % 4); return new r.init(d, c) } }, j = l.Utf8 = { stringify: function (a) { try { return decodeURIComponent(escape(n.stringify(a))) } catch (c) { throw Error("Malformed UTF-8 data"); } }, parse: function (a) { return n.parse(unescape(encodeURIComponent(a))) } },
    u = g.BufferedBlockAlgorithm = m.extend({
        reset: function () { this._data = new r.init; this._nDataBytes = 0 }, _append: function (a) { "string" == typeof a && (a = j.parse(a)); this._data.concat(a); this._nDataBytes += a.sigBytes }, _process: function (a) { var c = this._data, d = c.words, b = c.sigBytes, e = this.blockSize, f = b / (4 * e), f = a ? h.ceil(f) : h.max((f | 0) - this._minBufferSize, 0); a = f * e; b = h.min(4 * a, b); if (a) { for (var g = 0; g < a; g += e)this._doProcessBlock(d, g); g = d.splice(0, a); c.sigBytes -= b } return new r.init(g, b) }, clone: function () {
            var a = m.clone.call(this);
            a._data = this._data.clone(); return a
        }, _minBufferSize: 0
    }); g.Hasher = u.extend({
        cfg: m.extend(), init: function (a) { this.cfg = this.cfg.extend(a); this.reset() }, reset: function () { u.reset.call(this); this._doReset() }, update: function (a) { this._append(a); this._process(); return this }, finalize: function (a) { a && this._append(a); return this._doFinalize() }, blockSize: 16, _createHelper: function (a) { return function (c, d) { return (new a.init(d)).finalize(c) } }, _createHmacHelper: function (a) {
            return function (c, d) {
                return (new t.HMAC.init(a,
                    d)).finalize(c)
            }
        }
    }); var t = f.algo = {}; return f
}(Math);
(function (h) {
    for (var s = CryptoJS, f = s.lib, g = f.WordArray, q = f.Hasher, f = s.algo, m = [], r = [], l = function (a) { return 4294967296 * (a - (a | 0)) | 0 }, k = 2, n = 0; 64 > n;) { var j; a: { j = k; for (var u = h.sqrt(j), t = 2; t <= u; t++)if (!(j % t)) { j = !1; break a } j = !0 } j && (8 > n && (m[n] = l(h.pow(k, 0.5))), r[n] = l(h.pow(k, 1 / 3)), n++); k++ } var a = [], f = f.SHA256 = q.extend({
        _doReset: function () { this._hash = new g.init(m.slice(0)) }, _doProcessBlock: function (c, d) {
            for (var b = this._hash.words, e = b[0], f = b[1], g = b[2], j = b[3], h = b[4], m = b[5], n = b[6], q = b[7], p = 0; 64 > p; p++) {
                if (16 > p) a[p] =
                    c[d + p] | 0; else { var k = a[p - 15], l = a[p - 2]; a[p] = ((k << 25 | k >>> 7) ^ (k << 14 | k >>> 18) ^ k >>> 3) + a[p - 7] + ((l << 15 | l >>> 17) ^ (l << 13 | l >>> 19) ^ l >>> 10) + a[p - 16] } k = q + ((h << 26 | h >>> 6) ^ (h << 21 | h >>> 11) ^ (h << 7 | h >>> 25)) + (h & m ^ ~h & n) + r[p] + a[p]; l = ((e << 30 | e >>> 2) ^ (e << 19 | e >>> 13) ^ (e << 10 | e >>> 22)) + (e & f ^ e & g ^ f & g); q = n; n = m; m = h; h = j + k | 0; j = g; g = f; f = e; e = k + l | 0
            } b[0] = b[0] + e | 0; b[1] = b[1] + f | 0; b[2] = b[2] + g | 0; b[3] = b[3] + j | 0; b[4] = b[4] + h | 0; b[5] = b[5] + m | 0; b[6] = b[6] + n | 0; b[7] = b[7] + q | 0
        }, _doFinalize: function () {
            var a = this._data, d = a.words, b = 8 * this._nDataBytes, e = 8 * a.sigBytes;
            d[e >>> 5] |= 128 << 24 - e % 32; d[(e + 64 >>> 9 << 4) + 14] = h.floor(b / 4294967296); d[(e + 64 >>> 9 << 4) + 15] = b; a.sigBytes = 4 * d.length; this._process(); return this._hash
        }, clone: function () { var a = q.clone.call(this); a._hash = this._hash.clone(); return a }
    }); s.SHA256 = q._createHelper(f); s.HmacSHA256 = q._createHmacHelper(f)
})(Math);
(function () {
    var h = CryptoJS, s = h.enc.Utf8; h.algo.HMAC = h.lib.Base.extend({
        init: function (f, g) { f = this._hasher = new f.init; "string" == typeof g && (g = s.parse(g)); var h = f.blockSize, m = 4 * h; g.sigBytes > m && (g = f.finalize(g)); g.clamp(); for (var r = this._oKey = g.clone(), l = this._iKey = g.clone(), k = r.words, n = l.words, j = 0; j < h; j++)k[j] ^= 1549556828, n[j] ^= 909522486; r.sigBytes = l.sigBytes = m; this.reset() }, reset: function () { var f = this._hasher; f.reset(); f.update(this._iKey) }, update: function (f) { this._hasher.update(f); return this }, finalize: function (f) {
            var g =
                this._hasher; f = g.finalize(f); g.reset(); return g.finalize(this._oKey.clone().concat(f))
        }
    })
})();

function renderPayaxisFields() {

    var pp_hiddenFields = '<input type="hidden" name="pp_Version" id="pp_Version" value="2.0">' +
        '<input type="hidden" name="pp_IsRegisteredCustomer" id="pp_IsRegisteredCustomer">' +
        '<input type="hidden" name="pp_CustomerID" id="pp_CustomerID">' +
        '<input type="hidden" name="pp_CustomerEmail" id="pp_CustomerEmail">' +
        '<input type="hidden" name="pp_CustomerMobile" id="pp_CustomerMobile">' +
        '<input type="hidden" name="pp_ShouldTokenizeCardNumber" id="pp_ShouldTokenizeCardNumber" value="9539089762785530">' +
        '<input type="hidden" name="pp_TokenizedCardNumber" id="pp_TokenizedCardNumber">' +

        '<input type="hidden" name="IsReturningCustomer" id="IsReturningCustomer">' +
        '<input type="hidden" name="IsTokenizationAllowedForMerchant" id="IsTokenizationAllowedForMerchant">' +
        '<input type="hidden" name="IsAllowedCheck3DS" id="IsAllowedCheck3DS">' +
        '<input type="hidden" name="IsAllowedCVV" id="IsAllowedCVV">' +

        '<input type="hidden" name="MerchantResponseCode" id="MerchantResponseCode">' +
        '<input type="hidden" name="InstitutionID" id="InstitutionID">' +
        '<input type="hidden" name="TransactionReqLogID" id="TransactionReqLogID">' +
        '<input type="hidden" name="ReturningCustomerTransactionReAttempts" id="ReturningCustomerTransactionReAttempts">' +
        '<input type="hidden" name="CurrentTransactionReAttempts" id="CurrentTransactionReAttempts">' +
        '<input type="hidden" name="MerchantAuthentication" id="MerchantAuthentication">' +

        '<input type="hidden" name="pp_MerchantID" id="pp_MerchantID" value="">' +
        '<input type="hidden" name="pp_Language" id="pp_Language">' +
        '<input type="hidden" name="pp_TxnType" id="pp_TxnType" value="MPAY" >' +
        '<input type="hidden" name="pp_SubMerchantID" id="pp_SubMerchantID">' +
        '<input type="hidden" name="pp_Password" id="pp_Password">' +
        '<input type="hidden" name="pp_TxnRefNo" id="pp_TxnRefNo">' +
        '<input type="hidden" name="pp_Amount" id="pp_Amount">' +
        '<input type="hidden" name="pp_DiscountedAmount" id="pp_DiscountedAmount">' +
        '<input type="hidden" name="pp_DiscountBank" id="pp_DiscountBank">' +
        '<input type="hidden" name="pp_TxnCurrency" id="pp_TxnCurrency">' +
        '<input type="hidden" name="pp_TxnDateTime" id="pp_TxnDateTime">' +
        '<input type="hidden" name="pp_TxnExpiryDateTime" id="pp_TxnExpiryDateTime">' +
        '<input type="hidden" name="pp_BillReference" id="pp_BillReference">' +
        '<input type="hidden" name="pp_Description" id="pp_Description">' +
        '<input type="hidden" name="pp_ReturnURL" id="pp_ReturnURL">' +
        '<input type="hidden" name="ppmpf_1" id="ppmpf_1">' +
        '<input type="hidden" name="ppmpf_2" id="ppmpf_2">' +
        '<input type="hidden" name="ppmpf_3" id="ppmpf_3">' +
        '<input type="hidden" name="ppmpf_4" id="ppmpf_4">' +
        '<input type="hidden" name="ppmpf_5" id="ppmpf_5">' +
        '<input type="hidden" name="pp_UsageMode" id="pp_UsageMode" value="HPC">' +
        '<input type="hidden" name="pp_Frequency" id="pp_Frequency" value="SINGLE">' +
        '<input type="hidden" name="pp_InstrToken" id="pp_InstrToken">' +
        '<input type="hidden" name="pp_SecureHash" id="pp_SecureHash">' +
        '<input type="hidden" name="ResponseCode" id="ResponseCode"  />' +
        '<input type="hidden" name="ResponseMessage" id="ResponseMessage"  />' +
        '<input type="hidden" name="pp_RetreivalReferenceNo" id="pp_RetreivalReferenceNo"  />' +
        '<input type="hidden" name="pp_FinalAmount" id="pp_FinalAmount"  />' +
        '<input type="hidden" name="DoProcess" id="DoProcess" value="1" />' +
        '<input type="hidden" name="msg_it" id="msg_it"  />' +
        '<input type="hidden" name="C3DSecureID" id="C3DSecureID"  />' +
        '<input type="hidden" name="GateWayCode" id="GateWayCode" />' +
        '<input type="hidden" name="SummaryStatus" id="SummaryStatus"  />' +
        '<input type="hidden" name="Response" id="Response"  />';

    //$("#PayaxisFields").append('<input type="text" placeholder="Card Number" name="pp_CustomerCardNumber" id="pp_CustomerCardNumber" onkeypress="return numberinput(event)">' +
    //							' <br>' +
    //							' <input type="text" placeholder="Expiry Month" name="pp_CustomerCardExpiryM" id="pp_CustomerCardExpiryM" onkeypress="return numberinput(event)">' +
    //							' <br>' +
    //							' <input type="text" placeholder="Expiry Year" name="pp_CustomerCardExpiryY" id="pp_CustomerCardExpiryY" onkeypress="return numberinput(event)">' +
    //							' <br> ' +
    //							' <input type="text" placeholder="CVV" name="pp_CustomerCardCvv" id="pp_CustomerCardCvv" onkeypress="return numberinput(event)">' + ' <br >' + pp_hiddenFields);
    //onfocusout="CalculateDiscount()"


    var _currentYear = new Date().getFullYear(); //get current year
    var _currentMonth = new Date().getMonth() + 1; //get current year
    var _yearsToAppend = 23; // define years to append (ideally 10)
    var _monthsToAppend = 12; // define months to append (ideally 12)
    var _optionsList = "";
    var _optionsList2 = "";

    for (yl = _currentYear; yl < (_currentYear + _yearsToAppend); yl++) {
        _optionsList += "<option value='" + yl + "'>" + yl + "</option>"; // dynamically render year list
    }
    for (ml = _currentMonth; ml <= 12; ml++) {
        if (ml < 10) {
            ml = "0" + ml;
        }
        _optionsList2 += "<option value='" + ml + "'>" + ml + "</option>"; // dynamically render months list
    }

    $("#JazzCashFields").append('<div id="JazzFieldsWrapper" style="display: none;"><div id="UnMaskedCardNumber"><label>Card Number</label>' +
        '<input type="text" placeholder="Card Number" maxlength="16" name="pp_CustomerCardNumber" id="pp_CustomerCardNumber" onkeypress="return numberinput(event)" onfocusout="CalculateDiscount()" >' +
        ' <br></div>' +
        '<div id="MaskedCardNumber" style="display: none;"><label>Card Number</label>' + '<input type="text" placeholder="Card Number"  name="pp_CustomerCardNumberMasked" maxlength="16" id="pp_CustomerCardNumberMasked" onkeypress="return numberinput(event)" >' +
        ' <br><label>Card Type</label>' + '<input type="text" name="pp_CustomerCardType" id="pp_CustomerCardType" />' +
        ' <br><label>Issuing Bank</label>' + '<input type="text" name="pp_CustomerIssuingBank" id="pp_CustomerIssuingBank" />' +
        ' <br></div>' +
        '<label>Card Expiry Month</label>' +
        ' <select name="pp_CustomerCardExpiryM" id="pp_CustomerCardExpiryM">' +
        '<option value="">Month</option>' +
        //_optionsList2 +
        '<option value="01">01</option>' +
        '<option value="02">02</option>' +
        '<option value="03">03</option>' +
        '<option value="04">04</option>' +
        '<option value="05">05</option>' +
        '<option value="06">06</option>' +
        '<option value="07">07</option>' +
        '<option value="08">08</option>' +
        '<option value="09">09</option>' +
        '<option value="10">10</option>' +
        '<option value="11">11</option>' +
        '<option value="12">12</option>' +
        '</select>' +
        ' <br>' +
        //' <input type="text" placeholder="Expiry Year" name="pp_CustomerCardExpiryY" id="pp_CustomerCardExpiryY" onkeypress="return numberinput(event)">' +
        '<label>Card Expiry Year</label>' +
        '<select name="pp_CustomerCardExpiryY" id="pp_CustomerCardExpiryY">' +
        '<option value="">Year</option>' +
        _optionsList +
        '</select>' +
        ' <br> ' +
        '<div id="isCVVAllowed"><label>CVV</label>' +
        ' <input type="password" maxlength="3" placeholder="CVV" label="CVV" name="pp_CustomerCardCvv" id="pp_CustomerCardCvv" onkeypress="return numberinput(event)">' + ' <br >' + pp_hiddenFields
        + ' <br></div>' +
        '<div id="isTokenizationAllowed" style="display: none;"><label>Securely Save Your Card</label>' +
        '<div style="text-align: left;"><input style="width: auto;" type="checkbox" id="pp_ShouldTokenizeCardNumberCheck" name="pp_ShouldTokenizeCardNumberCheck" value="No" /></div><br /></div></div>'

    );

    $("#JazzCashFields").prepend('<div class="CustomResponseMessage"></div><div class="LoaderWrapper" style="display: none; position: absolute; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.1); text-align: center;"><p style="color: #000; position: absolute; left: 0; right: 0; margin: 0 auto; top: 50%; transform: translateY(-50%);">Processing...</p></div>');

    $('#pp_ShouldTokenizeCardNumberCheck').unbind('change');
    $('#pp_ShouldTokenizeCardNumberCheck').bind('change', function () {
        if ($('#pp_ShouldTokenizeCardNumberCheck:checked').length > 0) {
            $('#pp_ShouldTokenizeCardNumber').val('Yes');
        } else {
            $('#pp_ShouldTokenizeCardNumber').val('No');
        }
    })

    setTimeout(function () {
        $('#pp_ShouldTokenizeCardNumber').val('No');
        getMerchantConfiguration();
    }, 1000)

    $("#pp_CustomerCardNumber, #pp_CustomerCardCvv").off("input paste");
    $("#pp_CustomerCardNumber, #pp_CustomerCardCvv").on("input paste", function (e) {
        $(e.target).val($(e.target).val().replace(/\D/g, ''));
    });

}

function showCardModificationOptions() {

    $('.submitForm').hide();
    $("#JazzFieldsWrapper").hide();

    $('#JazzCashFields').after(`
        <div class="cardModificationModal" style="text-align: center;">
            <p><b>Sorry! Your transaction could not be processed since your card has been expired.</b></p>
            <br />
            <button type="button" onclick="RemoveCardProcess()">Remove Card</button>
            <button type="button" onclick="ShowUpdateCardScreen(merchantConfiguration)">Update Card</button>
            <button type="button" onclick="PostBackToMerchant()">Back</button>
        </div>
    `);

}

function showRetryOptions() {

    $('.submitForm').hide();
    $("#JazzFieldsWrapper").hide();

    $('#JazzCashFields').after(`
        <div class="showRetryOptionsModal" style="text-align: center;">
            <p><b>This service is temporary unavailable. Please retry after few second(s).</b></p>
            <br />
            <button type="button" onclick="RetryCardProcess()">Retry</button>
            <button type="button" onclick="PostBackToMerchant()">Back</button>
        </div>
    `);

}

function showConfigMismatchFailure() {

    $('.submitForm').hide();
    $("#JazzFieldsWrapper").hide();

    $('#JazzCashFields').after(`
        <div class="showConfigMismatchFailureModal" style="text-align: center;">
            <p><b>Sorry your request cannot be processed at the moment.</b></p>
            <br />
            <button type="button" onclick="PostBackToMerchant()">Back</button>
        </div>
    `);

}

function PostBackToMerchant() {

    var Fc = $("#onlineform").serialize();

    $.ajax({
        url: "https://sandbox.jazzcash.com.pk/hostedpay/purchasev20/PostBackToMerchant",
        type: 'POST',
        data: Fc,
        dataType: 'json',
        //async: false,
        success: function (response) {

            // $('.CustomResponseMessage').html('<p style="text-align: center; border: 1px solid #FF5722; margin: 10px; padding: 5px;">Redirecting back to merchant</p>').show();
            $("#Response").val(JSON.stringify(response));
            setTimeout(function () {
                $("#JazzCashFields").parents("form").unbind("submit");
                $("#JazzCashFields").parents("form").submit();
            }, timer)

        },
        error: function () {
            console.log("Error occurred - 1");
        }
    });

}

function RetryCardProcess() {

    CurrentTransactionReAttempts++;

    if (CurrentTransactionReAttempts <= ReturningCustomerTransactionReAttempts) {

        var Fc = $("#onlineform").serialize();

        $.ajax({
            url: "https://sandbox.jazzcash.com.pk/hostedpay/purchasev20/RetrieveTokenTemporaryError",
            type: 'POST',
            data: Fc,
            dataType: 'json',
            //async: false,
            success: function (response) {
                if (response.ResponseCode === "T000") {
                    //show number of attempt availble
                    $('.CustomResponseMessage').html('<p style="text-align: center; border: 1px solid #FF5722; margin: 10px; padding: 5px;">' + response.ResponseMessage + '</p>').show();
                    applyMerchantConfiguration(response.MerchantConfigurationsResponse);
                } else if (response.ResponseCode === "HP06") {
                    // remain on same screen and show incremented retry attempt
                    console.log("HP06");
                }
                else {
                    setTimeout(function () {
                        PostBackToMerchant();
                    }, timer)
                }
            },
            error: function () {
                console.log("Error occurred - 1");
            }
        });

    } else {
        //$('.CustomResponseMessage').html('<p style="text-align: center; border: 1px solid #FF5722; margin: 10px; padding: 5px;">Max tries exhausted</p>').show();
        setTimeout(function () {
            PostBackToMerchant();
        }, timer)
    }

}

function RemoveCardProcess() {

    var removeConfirm = confirm("Are you sure you want to remove this card?");
    if (removeConfirm == true) {
        var Fc = $("#onlineform").serialize();

        $.ajax({
            url: "https://sandbox.jazzcash.com.pk/hostedpay/purchasev20/RemoveCard",
            type: 'POST',
            data: Fc,
            dataType: 'json',
            //async: false,
            success: function (response) {
                console.log(response);
                if (response.pp_TokenizationResponseCode === "T00") {
                    $('.CustomResponseMessage').html('<p style="text-align: center; border: 1px solid #FF5722; margin: 10px; padding: 5px;">Your card has been removed successfully.</p>').show();
                    setTimeout(function () {
                        PostBackToMerchant();
                    }, timer)
                } else {
                    $('.CustomResponseMessage').html('<p style="text-align: center; border: 1px solid #FF5722; margin: 10px; padding: 5px;">Sorry your card count not be removed.</p>').show();
                    setTimeout(function () {
                        PostBackToMerchant();
                    }, timer)
                }
            },
            error: function () {
                console.log("Error occurred - 1");
            }
        });
    } else {
        return false;
    }

}

function ShowUpdateCardScreen(response) {

    var MaskedCardNumber = response.ReturningCustomerInstrument.MaskedCardNumber;
    var CardExpiry = response.ReturningCustomerInstrument.CardExpiry.split('/');
    var CardExpiryMonth = CardExpiry[0];
    var CardExpiryYear = CardExpiry[1];
    pp_CustomerIssuingBank = response.ReturningCustomerInstrument.IssuingBank;
    pp_CustomerCardType = response.ReturningCustomerInstrument.CardType;

    $('.cardModificationModal, .CustomResponseMessage').hide();
    $('#JazzCashFields').show();
    $('.showRetryOptionsModal').hide();
    $('#JazzFieldsWrapper').show();
    $('.submitForm').hide();
    $('#InitUpdateCard').show();


    $('#pp_CustomerCardNumberMasked').val(MaskedCardNumber).focus().prop('readonly', true).css({ 'cursor': 'not-allowed', 'background': '#eee' });
    $('#pp_CustomerCardExpiryM').val(CardExpiryMonth);
    $('#pp_CustomerCardExpiryY').val(CardExpiryYear);
    $('#pp_CustomerIssuingBank').val(pp_CustomerIssuingBank).prop('readonly', true).css({ 'cursor': 'not-allowed', 'background': '#eee' });
    $('#pp_CustomerCardType').val(pp_CustomerCardType).prop('readonly', true).css({ 'cursor': 'not-allowed', 'background': '#eee' });

    $('#UnMaskedCardNumber').hide();
    $('#MaskedCardNumber').show();
    $('#isCVVAllowed').show();
    $('#isTokenizationAllowed').hide();

}

function UpdateCardProcess() {

    var Fc = $("#onlineform").serialize();

    $.ajax({
        url: "https://sandbox.jazzcash.com.pk/hostedpay/purchasev20/UpdateCard",
        type: 'POST',
        data: Fc,
        dataType: 'json',
        //async: false,
        success: function (response) {
            //console.log(response);
            if (response.ResponseCode === "T000") {
                $('.CustomResponseMessage').html('<p style="text-align: center; border: 1px solid #FF5722; margin: 10px; padding: 5px;">Your card expiry has been updated successfully</p>').show();
                setTimeout(function () {
                    applyMerchantConfiguration(response.MerchantConfigurationsResponse);
                }, timer)
            } else if (response.ResponseCode === "HP98") {
                $('.CustomResponseMessage').html('<p style="text-align: center; border: 1px solid #FF5722; margin: 10px; padding: 5px;">' + response.ResponseMessage + '</p>').show();
            } else {
                $('.CustomResponseMessage').html('<p style="text-align: center; border: 1px solid #FF5722; margin: 10px; padding: 5px;">Sorry! your card could not updated at this moment</p>').show();
                    setTimeout(function () {
                    PostBackToMerchant();
                }, timer)
            }
        },
        error: function () {
            console.log("Error occurred - 1");
        }
    });

}

function populateCardFields(response) {

    var MaskedCardNumber = response.ReturningCustomerInstrument.MaskedCardNumber;
    var UnMaskedCardNumber = response.ReturningCustomerInstrument.CardNumber;
    var CardExpiry = response.ReturningCustomerInstrument.CardExpiry.split('/');
    var CardExpiryMonth = CardExpiry[0];
    var CardExpiryYear = CardExpiry[1];
    pp_CustomerIssuingBank = response.ReturningCustomerInstrument.IssuingBank;
    pp_CustomerCardType = response.ReturningCustomerInstrument.CardType;

    $('.cardModificationModal, .CustomResponseMessage').hide();
    $('#JazzCashFields').show();
    $('.showRetryOptionsModal').hide();
    $('#JazzFieldsWrapper, .submitForm').show();

    $('#pp_CustomerCardNumber').val(UnMaskedCardNumber).focus();
    $('#pp_CustomerCardNumberMasked').val(MaskedCardNumber).focus().prop('readonly', true).css({ 'cursor': 'not-allowed', 'background': '#eee' });
    $('#pp_CustomerCardExpiryM').val(CardExpiryMonth).prop('readonly', true).css({ 'cursor': 'not-allowed', 'background': '#eee' });
    $('#pp_CustomerCardExpiryY').val(CardExpiryYear).prop('readonly', true).css({ 'cursor': 'not-allowed', 'background': '#eee' });
    $('#pp_CustomerIssuingBank').val(pp_CustomerIssuingBank).prop('readonly', true).css({ 'cursor': 'not-allowed', 'background': '#eee' });
    $('#pp_CustomerCardType').val(pp_CustomerCardType).prop('readonly', true).css({ 'cursor': 'not-allowed', 'background': '#eee' });
    $('#pp_CustomerCardCvv').val("");

    $('#UnMaskedCardNumber').hide();
    $('#MaskedCardNumber').show();

    CalculateDiscount();

}

function applyMerchantConfiguration(response) {

    AuthenticationToken = response.AuthenticationToken;
    IsAllowedCVV = response.IsAllowedCVV;
    IsAllowedCheck3DS = response.IsAllowedCheck3DS;
    IsExpiredCard = response.IsExpiredCard;
    IsReturningCustomer = response.IsReturningCustomer;
    IsTemporaryError = response.IsTemporaryError;
    IsTokenizationAllowedForMerchant = response.IsTokenizationAllowedForMerchant;
    ResponseCode = response.ResponseCode;
    ResponseMessage = response.ResponseMessage;
    InstitutionID = response.InstitutionID;
    MerchantResponseCode = response.MerchantResponseCode;
    TransactionReqLogID = response.TransactionReqLogID;
    IsRegisteredCustomer = $('#pp_IsRegisteredCustomer').val();
    TokenizedCardNumber = $('#pp_TokenizedCardNumber').val();
    ReturningCustomerTransactionReAttempts = response.ReturningCustomerTransactionReAttempts;
    MerchantAuthentication = response.MerchantAuthentication;

    // Populating New Common Fields
    $('#IsReturningCustomer').val(IsReturningCustomer);
    $('#IsTokenizationAllowedForMerchant').val(IsTokenizationAllowedForMerchant);
    $('#IsAllowedCheck3DS').val(IsAllowedCheck3DS);
    $('#IsAllowedCVV').val(IsAllowedCVV);
    $('#InstitutionID').val(InstitutionID);
    $('#MerchantResponseCode').val(MerchantResponseCode);
    $('#TransactionReqLogID').val(TransactionReqLogID);
    $('#CurrentTransactionReAttempts').val(CurrentTransactionReAttempts);
    $('#ReturningCustomerTransactionReAttempts').val(ReturningCustomerTransactionReAttempts);
    $('#MerchantAuthentication').val(MerchantAuthentication);
    $('#ResponseCode').val(ResponseCode);
    $('#ResponseMessage').val(ResponseMessage);

    switch (ResponseCode) {
        case "HP00": // CASE HP00

            if (IsRegisteredCustomer === "No" && IsTokenizationAllowedForMerchant === false && IsReturningCustomer === false && IsAllowedCVV === true && IsAllowedCheck3DS === true) {
                $('#isTokenizationAllowed').hide();
                $('#JazzFieldsWrapper').show();
                $('.submitForm').show();
                $('#InitUpdateCard').hide();
            } else {
                showConfigMismatchFailure();
                $('#InitUpdateCard').hide();
            }
            break;
        case "HP01": // CASE HP01

            if (IsRegisteredCustomer === "Yes" && IsReturningCustomer === false && IsTokenizationAllowedForMerchant === true && IsAllowedCVV === true && IsAllowedCheck3DS === true) {
                $('#isTokenizationAllowed').show();
                $('#JazzFieldsWrapper').show();
                $('.submitForm').show();
                $('#InitUpdateCard').hide();
                $('#pp_TokenizedCardNumber').val('Yes');
            } else {
                showConfigMismatchFailure();
                $('#InitUpdateCard').hide();
            }
            break;
        case "HP02": // CASE HP02

            if (IsRegisteredCustomer === "Yes" && IsReturningCustomer === true && IsTokenizationAllowedForMerchant === true && IsAllowedCVV === true && IsAllowedCheck3DS === false) {
                $('#isTokenizationAllowed').hide();
                populateCardFields(response);
                $('#InitUpdateCard').hide();
            } else {
                showConfigMismatchFailure();
                $('#InitUpdateCard').hide();
            }
            break;
        case "HP03": // CASE HP03

            if (IsRegisteredCustomer === "Yes" && IsReturningCustomer === true && IsTokenizationAllowedForMerchant === true && IsAllowedCVV === true && IsAllowedCheck3DS === true) {
                $('#isTokenizationAllowed').hide();
                populateCardFields(response);
                $('#InitUpdateCard').hide();
            } else {
                showConfigMismatchFailure();
                $('#InitUpdateCard').hide();
            }
            break;
        case "HP04": // CASE HP04

            if (IsRegisteredCustomer === "Yes" && IsReturningCustomer === true && IsTokenizationAllowedForMerchant === true && IsAllowedCVV === false && IsAllowedCheck3DS === false) {
                $('#isCVVAllowed').hide();
                $('#isTokenizationAllowed').hide();
                populateCardFields(response);
                $('#InitUpdateCard').hide();
            } else {
                showConfigMismatchFailure();
                $('#InitUpdateCard').hide();
            }
            break;
        case "HP05": // CASE HP05

            if (IsRegisteredCustomer === "Yes" && IsReturningCustomer === true && IsTokenizationAllowedForMerchant === true && IsAllowedCVV === false && IsAllowedCheck3DS === true) {
                $('#isCVVAllowed').hide();
                $('#isTokenizationAllowed').hide();
                populateCardFields(response);
                $('#InitUpdateCard').hide();
            } else {
                showConfigMismatchFailure();
                $('#InitUpdateCard').hide();
            }
            break;
        case "HP06": // CASE HP06

            if (IsRegisteredCustomer === "Yes" && IsReturningCustomer === true && IsTokenizationAllowedForMerchant === true && IsTemporaryError === true) {
                $('#isCVVAllowed').hide();
                $('#isTokenizationAllowed').hide();
                $('#InitUpdateCard').hide();
                showRetryOptions();
            } else {
                showConfigMismatchFailure();
                $('#InitUpdateCard').hide();
            }
            break;
        case "HP07": // CASE HP07

            if (IsRegisteredCustomer === "Yes" && IsReturningCustomer === true && IsTokenizationAllowedForMerchant === true && IsExpiredCard === true) {
                $('#isCVVAllowed').hide();
                $('#isTokenizationAllowed').hide();
                //populateCardFields(response);
                showCardModificationOptions();
            } else {
                showConfigMismatchFailure();
                $('#InitUpdateCard').hide();
            }
            break;
        case "HP08": // CASE HP08

            if (IsRegisteredCustomer === "Yes" && IsReturningCustomer === true && IsTokenizationAllowedForMerchant === true && IsAllowedCVV === true) {
                $('#isCVVAllowed').show();
                $('#isTokenizationAllowed').hide();
                populateCardFields(response);
                $('#InitUpdateCard').hide();
                //showCardModificationOptions();
            } else {
                showConfigMismatchFailure();
                $('#InitUpdateCard').hide();
            }
            break;
        case "HP09": // CASE HP09

            if (IsRegisteredCustomer === "Yes" && IsReturningCustomer === true && IsTokenizationAllowedForMerchant === true && IsAllowedCVV === false) {
                $('#isCVVAllowed').hide();
                $('#isTokenizationAllowed').hide();
                populateCardFields(response);
                $('#InitUpdateCard').hide();
                //showCardModificationOptions();
            } else {
                showConfigMismatchFailure();
                $('#InitUpdateCard').hide();
            }
            break;
        case "HP96": // CASE HP96 [Error Case]
            $('.CustomResponseMessage').html('<p style="text-align: center; border: 1px solid #FF5722; margin: 10px; padding: 5px;">' + response.ResponseMessage + '</p>').show();
            $('.submitForm').hide();
            $('#JazzFieldsWrapper').hide();
            $('#InitUpdateCard').hide();
            setTimeout(function () {
                PostBackToMerchant();
            }, timer)

            break;
        case "HP97": // CASE HP97 [Error Case]
            $('.CustomResponseMessage').html('<p style="text-align: center; border: 1px solid #FF5722; margin: 10px; padding: 5px;">' + response.ResponseMessage + '</p>').show();
            $('.submitForm').hide();
            $('#JazzFieldsWrapper').hide();
            $('#InitUpdateCard').hide();
            setTimeout(function () {
                PostBackToMerchant();
            }, timer)

            break;
        case "HP98": // CASE HP98 [Error Case]
            $('.CustomResponseMessage').html('<p style="text-align: center; border: 1px solid #FF5722; margin: 10px; padding: 5px;">' + response.ResponseMessage + '</p>').show();
            $('.submitForm').hide();
            $('#JazzFieldsWrapper').hide();
            $('#InitUpdateCard').hide();
            setTimeout(function () {
                PostBackToMerchant();
            }, timer)

            break;
        case "HP99": // CASE HP99 [Error Case]
            $('.CustomResponseMessage').html('<p style="text-align: center; border: 1px solid #FF5722; margin: 10px; padding: 5px;">' + response.ResponseMessage + '</p>').show();
            $('.submitForm').hide();
            $('#JazzFieldsWrapper').hide();
            $('#InitUpdateCard').hide();
            setTimeout(function () {
                PostBackToMerchant();
            }, timer)

            break;
        default:
            $('.CustomResponseMessage').html('<p style="text-align: center; border: 1px solid #FF5722; margin: 10px; padding: 5px;">Sorry your request cannot be processed at the moment.</p>').show();
            $('.submitForm').hide();
            $('#JazzFieldsWrapper').hide();
            $('#InitUpdateCard').hide();
            setTimeout(function () {
                PostBackToMerchant();
            }, timer)

    }

}

function getMerchantConfiguration() {

    var Fc = $("#onlineform").serialize();

    $.ajax({
        url: "https://sandbox.jazzcash.com.pk/hostedpay/purchasev20/GetMerchantConfigurations",
        type: 'POST',
        data: Fc,
        dataType: 'json',
        //async: false,
        success: function (response) {
            applyMerchantConfiguration(response);
            merchantConfiguration = response;
            $('#preLoadingText').hide(); 
        },
        error: function () {
            console.log("Error occurred - 1");
        }
    });

}

function numberinput(e) {
    var unicode = e.charCode ? e.charCode : e.keyCode;
    if (unicode === 8 || unicode === 9) { return true; }
    return (unicode < 48 || unicode > 57) ? false : true;
}

function CalculateDiscount() {
    $('#JazzCashErrorDiv').hide();
    var cardNumChk = $('#pp_CustomerCardNumber').val();
    var test = cardNumChk.match(/^[0-9]+$/);

    if (cardNumChk != '' && cardNumChk.length == 16 && test != null && checkPayAxisCardNumber(cardNumChk) == true) {
        $('#JazzCashSuccessDiv').hide();
        var Fc = $("#onlineform").serialize();
        var tmpCardNum = $('#pp_CustomerCardNumber').val();

        setTimeout(function () {
            $.ajax({
                url: "https://sandbox.jazzcash.com.pk/hostedpay/purchasev20/CalculateDiscount",
                type: 'POST',
                data: Fc,
                dataType: 'json',
                //async: false,
                beforeSend: function () {
                    $('#pp_CustomerCardNumber').val('Calculating Discount...').css('background', '#eee');
                },
                success: function (data) {
                    //console.log(data);

                    $('#JazzCashErrorDiv').fadeOut("fast").html("");
                    if (data.ResponseCode == "0000") {
                        $('#DoProcess').val("1");
                        $('#pp_CustomerCardNumber').val(tmpCardNum).attr('readonly', 'readonly');
                        var GivenAmount = $("#pp_Amount").val();
                        var amountdisc = data.AmountAfterDiscountCalculation.toString();

                        var SubstrGivenAmount = GivenAmount.substring(0, GivenAmount.length - 2);
                        var calculateAmount = amountdisc.substring(0, amountdisc.length - 2);

                        if (Number(calculateAmount) < Number(SubstrGivenAmount)) {
                            $("#pp_FinalAmount").val(data.AmountAfterDiscountCalculation);
                            $('#JazzCashSuccessDiv').html('Discount has been applied , Original amount: <span style="text-decoration: line-through;">' + $('#pp_TxnCurrency').val() + ' ' + SubstrGivenAmount + '.00 </span> ' + ' new amount is: ' + $('#pp_TxnCurrency').val() + ' ' + data.UIFinalAmount).fadeIn();
                        }
                        else if (Number(calculateAmount) == Number(SubstrGivenAmount)) {
                            $('#JazzCashSuccessDiv').html('Amount: ' + $('#pp_TxnCurrency').val() + ' ' + SubstrGivenAmount + '.00').fadeIn();
                        }

                        isDiscountCalculated = true;

                    }
                    else if (data.ResponseCode == "362") {
                        $('#pp_CustomerCardNumber').val(tmpCardNum).css('background', '');
                        $('#DoProcess').val("0");
                        $('#JazzCashErrorDiv').html(data.ResponseMessage).fadeIn();
                        $('#JazzCashErrorDiv').show();
                    }
                    else {
                        $('#pp_CustomerCardNumber').val(tmpCardNum).css('background', '');
                    }
                },
                error: function () {
                    console.log("error");

                }
            });
        }, 500)
    } else {
        $('#JazzCashErrorDiv').html("Please enter 16 digit valid card number").fadeIn();
        $('#JazzCashErrorDiv').show();
    }
}

function populateJazzCashFields(pp_payload) {

    $("#pp_IsRegisteredCustomer").val(pp_payload.pp_IsRegisteredCustomer);
    $("#pp_CustomerID").val(pp_payload.pp_CustomerID);
    $("#pp_CustomerEmail").val(pp_payload.pp_CustomerEmail);
    $("#pp_CustomerMobile").val(pp_payload.pp_CustomerMobile);
    $("#pp_ShouldTokenizeCardNumber").val(pp_payload.pp_ShouldTokenizeCardNumber);
    $("#pp_TokenizedCardNumber").val(pp_payload.pp_TokenizedCardNumber);

    $("#IsReturningCustomer").val(pp_payload.IsReturningCustomer);
    $("#IsTokenizationAllowedForMerchant").val(pp_payload.IsTokenizationAllowedForMerchant);
    $("#IsAllowedCheck3DS").val(pp_payload.IsAllowedCheck3DS);
    $("#IsAllowedCVV").val(pp_payload.IsAllowedCVV);

    $("#InstitutionID").val(pp_payload.InstitutionID);
    $("#MerchantResponseCode").val(pp_payload.MerchantResponseCode);
    $("#TransactionReqLogID").val(pp_payload.TransactionReqLogID);

    $("#pp_TxnType").val("MPAY");
    $("#pp_MerchantID").val(pp_payload.pp_MerchantID);
    $("#pp_Language").val(pp_payload.pp_Language);
    $("#pp_SubMerchant").val(pp_payload.pp_SubMerchantID);
    $("#pp_Password").val(pp_payload.pp_Password);
    $("#pp_TxnRefNo").val(pp_payload.pp_TxnRefNo);
    $("#pp_Amount").val(pp_payload.pp_Amount);
    $("#pp_DiscountedAmount").val(pp_payload.pp_DiscountedAmount);
    $("#pp_DiscountBank").val(pp_payload.pp_DiscountBank);
    $("#pp_TxnCurrency").val(pp_payload.pp_TxnCurrency);
    $("#pp_TxnExpiryDateTime").val(pp_payload.pp_TxnExpiryDateTime);
    $("#pp_TxnDateTime").val(pp_payload.pp_TxnDateTime);
    $("#pp_BillReference").val(pp_payload.pp_BillReference);
    $("#pp_Description").val(pp_payload.pp_Description);
    $("#pp_ReturnURL").val(pp_payload.pp_ReturnURL);
    $("#ppmpf_1").val(pp_payload.ppmpf_1);
    $("#ppmpf_2").val(pp_payload.ppmpf_2);
    $("#ppmpf_3").val(pp_payload.ppmpf_3);
    $("#ppmpf_4").val(pp_payload.ppmpf_4);
    $("#ppmpf_5").val(pp_payload.ppmpf_5);
    $("#pp_SecureHash").val(pp_payload.pp_SecureHash);
    return true;
}

function Validate() {
    if (!isDiscountCalculated) {
        $('#JazzCashErrorDiv').html("Please wait for discount calculation.").fadeIn();
        $('#JazzCashErrorDiv').show();
        console.log("Awaiting discount calculation.");
        return false;
    }
    if ($('#pp_Frequency').val() == "SINGLE") { // Card Payment
        if ($('pp_CustomerCardNumber').val() == '' || $('pp_CustomerCardNumber').val() == null) {
            $('pp_CustomerCardNumber').css('background', 'rgba(196, 21, 28, 0.1)').focus();
            $('.errorDiv').show().html('<p>Card Number cannot be empty</p>');
            $('#pp_CustomerCardNumber').removeAttr('readonly');
            return false;
        } else if ($('pp_CustomerCardNumber').val().length > 16) {
            $('.errorDiv').show().html('<p>Card Number should be a 16 digit number</p>');
            $('#pp_CustomerCardNumber').removeAttr('readonly');
        } else if (result == false) {
            $('.errorDiv').show().html('<p>Card Number is not valid</p>');
            $('#pp_CustomerCardNumber').removeAttr('readonly');
            return false;
        }
        //} else if (cardPaymentNumber == '' || cardPaymentNumber == null) {
        //    $('#cardPayment2').css('background', 'rgba(196, 21, 28, 0.1)').focus();
        //    $('.errorDiv').show().html('<p>Mobile Number cannot be empty</p>');
        //    return false;
        //} else if (getFirstNumberCard != 0 || getSecondNumberCard != 3) {
        //    $('#cardPayment2').css('background', 'rgba(196, 21, 28, 0.1)').focus();
        //    $('.errorDiv').show().html('<p>Mobile Number should start with 03</p>');
        //    return false;
        //} else if (cardPaymentNumber.length > 11) {
        //    $('#cardPayment2').css('background', 'rgba(196, 21, 28, 0.1)').focus();
        //    $('.errorDiv').show().html('<p>Mobile Number should be an 11 digit number</p>');
        //    return false;
        //}
        else if ($('#pp_CustomerCardCvv').val() == '' || $('#pp_CustomerCardCvv').val() == null) {
            $('#pp_CustomerCardCvv').css('background', 'rgba(196, 21, 28, 0.1)').focus();
            $('.errorDiv').show().html('<p>CVV cannot be empty</p>');
            return false;
        } else if (cvvLength != 3) {
            $('#pp_CustomerCardCvv').css('background', 'rgba(196, 21, 28, 0.1)').focus();
            $('.errorDiv').show().html('<p>CVV is not valid</p>');
            return false;
        } else if ($('#pp_CustomerCardExpiryY').val() == '0' || $('#pp_CustomerCardExpiryY').val() == null) {
            $('.errorDiv').show().html('<p>Please select card expiry</p>');
            return false;
        } else if ($('#pp_CustomerCardExpiryM').val() == '0' || $('#pp_CustomerCardExpiryM').val() == null) {
            $('.errorDiv').show().html('<p>Please select card expiry</p>');
            return false;
        }
        else {
            return true;
        }
    }
    else {
        return true;
    }
}

function DoPay() {
    var Fc = $("#onlineform").serialize();
    var doPayStatus = false;
    var SStatus = "";


    //$.ajax({
    //    type: "POST",
    //    url: "Purchase/DoPay",
    //    data: Fc,
    //    dataType: 'json',
    //    async: false,
    //}).done(function (data) {
    //    console.log("data: " + data);
    //    $("#pp_CustomerCardNumber").val("");
    //    $("#pp_CustomerCardExpiryM").val("");
    //    $("#pp_CustomerCardExpiryY").val("");
    //    $("#pp_CustomerCardCvv").val("");
    //    $('#responseDiv').show();
    //    $("#pp_ResponseCode").val(data.ResponseCode);
    //    $("#pp_ResponseMessage").val(data.ResponseMessage);
    //    $("#msg_it").val(data.InstrumentToken);
    //    $("#pp_InstrToken").val(data.pp_InstrToken);
    //    $("#pp_RetreivalReferenceNo").val(data.pp_RetreivalReferenceNo);
    //    doPayStatus = true;
    //}).fail(function () {
    //    console.log("Ajax error");
    //    doPayStatus = false;
    //});




    $.ajax({
        url: "https://sandbox.jazzcash.com.pk/hostedpay/purchasev20/DoTransaction",
        type: 'POST',
        data: Fc,
        dataType: 'json',
        //async: false,
        success: function (data) {
            $("#pp_CustomerCardNumber").val("");
            $("#pp_CustomerCardExpiryM").val("");
            $("#pp_CustomerCardExpiryY").val("");
            $("#pp_CustomerCardCvv").val("");
            $('#responseDiv').show();
            //$("#pp_ResponseCode").val(data.ResponseCode);
            //$("#pp_ResponseMessage").val(data.ResponseMessage);
            $("#ResponseCode").val(data.ResponseCode);
            $("#ResponseMessage").val(data.ResponseMessage);
            $("#msg_it").val(data.pp_InstrToken);
            $("#pp_InstrToken").val(data.pp_InstrToken);
            $("#pp_RetreivalReferenceNo").val(data.pp_RetreivalReferenceNo);
            $("#pp_SecureHash").val(data.SecureHash);
            $("#C3DSecureID").val(data.C3DSecureID);
            $("#GateWayCode").val(data.GateWayCode);
            $("#SummaryStatus").val(data.SummaryStatus);
            $("#pp_Language").val(data.pp_Language);
            $("#Response").val(JSON.stringify(data));
            $("pp_TxnExpiryDateTime").val(data.pp_TxnExpiryDateTime);


            if (data.SummaryStatus == "CARD_ENROLLED") {
                $("#stagingForm").html(data.AR_Simple_Html);
                $('#myForm').html($("#stagingForm").find("form").html());
            }
            doPayStatus = true;
            SStatus = data.SummaryStatus;

            //if (IsAllowedCheck3DSEnabled === true) {

            //}



            processDoPay([doPayStatus, SStatus]);
        },
        error: function () {
            console.log("Error occurred - 1");
            doPayStatus = false;
            processDoPay([doPayStatus, SStatus]);
        }
    });
}

function checkPayAxisCardNumber($card) {
    var c = $card;
    var cl = parseInt(c.substr(c.length - 1));
    var c = c.slice(0, -1)
    var c = c.split("").reverse().join("");
    var c = c.split("");
    var a = 2;
    var cm = [];
    for (var i = 0; i < c.length; i++) {
        if (a % 2 == 0) {
            var t = c[i] * 2;
            if (t > 9) { var t = (t - 9); }
            cm.push(t);
        } else { cm.push(parseInt(c[i])); }
        a++;
    }
    var f = 0;
    for (var i = 0; i < cm.length; i++) { f += cm[i]; }
    f = f + cl;
    if (f % 10 == 0) { return true; } else { return false; }
}

function validatePayaxisCheckoutForm() {
    if (!isDiscountCalculated) {
        $('#JazzCashErrorDiv').html("Please wait for discount calculation.").fadeIn();
        $('#JazzCashErrorDiv').show();
        console.log("Awaiting discount calculation.");
        return false;
    }
    if ($('#pp_Frequency').val() == "SINGLE") {
        // Getting and setting values
        var pp_CustomerCardNumber = $('#pp_CustomerCardNumber').val();
        var pp_CustomerCardExpiryM = $('#pp_CustomerCardExpiryM').val();
        var pp_CustomerCardExpiryY = $('#pp_CustomerCardExpiryY').val();
        var pp_CustomerCardCvv = $('#pp_CustomerCardCvv').val();
        var currentPADate = new Date();
        var currentPAMonth = currentPADate.getMonth() + 1;
        var currentPAFullYear = currentPADate.getFullYear();
        //console.log(currentPAFullYear.toString().substr(2,4));



        // Clearing all Invalid Classes and removing all Error Messages
        $('form').each(function () {
            $('input').removeClass('invalid');
            $('input').removeAttr('errorMessage');
        })

        // Card Number Validations
        if (pp_CustomerCardNumber == '' || pp_CustomerCardNumber == null) { // Checking for null
            $('#pp_CustomerCardNumber').addClass('invalid').attr('errorMessage', msg_InvalidCardNumber).focus();
            //alert($('#pp_CustomerCardNumber').attr('errormessage'));
            $('#JazzCashErrorDiv').html(msg_InvalidCardNumber).fadeIn();
            return false;
        }
        else if (isNaN($('#pp_CustomerCardNumber').val())) { // Checking for alpha chars
            $('#pp_CustomerCardNumber').addClass('invalid').attr('errorMessage', msg_NumbersOnlyCard).focus();
            $('#JazzCashErrorDiv').html(msg_NumbersOnlyCard).fadeIn();
            return false;
        }
        else if (pp_CustomerCardNumber.length > 16) { // Checking card number length
            $('#pp_CustomerCardNumber').addClass('invalid').attr('errorMessage', msg_InvalidCardNumber).focus();
            $('#JazzCashErrorDiv').html(msg_InvalidCardNumber).fadeIn();
            return false;
        } else if (checkPayAxisCardNumber(pp_CustomerCardNumber) == false) { // validating card number usign Luhn Algo
            $('#pp_CustomerCardNumber').addClass('invalid').attr('errorMessage', msg_InvalidCardNumber).focus();
            $('#JazzCashErrorDiv').html(msg_InvalidCardNumber).fadeIn();
            return false;
        }

        if (IsAllowedCVV != false) {

            // CVV Number Validations
            if (pp_CustomerCardCvv == '' || pp_CustomerCardCvv == null) { // Checking for null
                $('#pp_CustomerCardCvv').addClass('invalid').attr('errorMessage', msg_InvalidCVV).focus();
                $('#JazzCashErrorDiv').html(msg_InvalidCVV).fadeIn();
                return false;
            } else if (isNaN($('#pp_CustomerCardCvv').val())) { // Checking for alpha chars
                $('#pp_CustomerCardCvv').addClass('invalid').attr('errorMessage', msg_NumbersOnlyCVV).focus();
                $('#JazzCashErrorDiv').html(msg_NumbersOnlyCVV).fadeIn();
                return false;
            }
            else if (pp_CustomerCardCvv.length != 3) { // checking cvv number length
                $('#pp_CustomerCardCvv').addClass('invalid').attr('errorMessage', msg_InvalidCVV).focus();
                $('#JazzCashErrorDiv').html(msg_InvalidCVV).fadeIn();
                return false;
            }

        }

        // Card Month Basic Validation
        if (pp_CustomerCardExpiryM == '' || pp_CustomerCardExpiryM == null) { // Checking for null
            $('#pp_CustomerCardExpiryM').addClass('invalid').attr('errorMessage', msg_InvalidMonth).focus();
            $('#JazzCashErrorDiv').html(msg_InvalidMonth).fadeIn();
            return false;
        }
        else if (isNaN($('#pp_CustomerCardExpiryM').val())) { // Checking for alpha chars
            $('#pp_CustomerCardExpiryM').addClass('invalid').attr('errorMessage', msg_NumbersOnly).focus();
            $('#JazzCashErrorDiv').html(msg_NumbersOnly).fadeIn();
            return false;
        }
        else if (pp_CustomerCardExpiryM > 12) { // Checking if month is greater than 12
            $('#pp_CustomerCardExpiryM').addClass('invalid').attr('errorMessage', msg_InvalidMonth).focus();
            $('#JazzCashErrorDiv').html(msg_InvalidMonth).fadeIn();
            return false;
        }

        // Card Year Basic Validation
        if (pp_CustomerCardExpiryY == '' || pp_CustomerCardExpiryY == null) { // Checking for null
            $('#pp_CustomerCardExpiryY').addClass('invalid').attr('errorMessage', msg_InvalidYear).focus();
            $('#JazzCashErrorDiv').html(msg_InvalidYear).fadeIn();
            return false;
        }
        else if (isNaN($('#pp_CustomerCardExpiryY').val())) { // Checking for alpha chars
            $('#pp_CustomerCardExpiryY').addClass('invalid').attr('errorMessage', msg_NumbersOnly).focus();
            $('#JazzCashErrorDiv').html(msg_NumbersOnly).fadeIn();
            return false;
        }

        // Card Month and Year Validation

        if (pp_CustomerCardExpiryM <= currentPAMonth && pp_CustomerCardExpiryY <= currentPAFullYear) { // Checking if month is > current month & if year is greater than current year
            $('#pp_CustomerCardExpiryM').addClass('invalid').attr('errorMessage', msg_ExpiryMonthnYear).focus();
            $('#pp_CustomerCardExpiryY').addClass('invalid').attr('errorMessage', msg_ExpiryYear).focus();
            $('#JazzCashErrorDiv').html(msg_ExpiryMonthnYear + " " + msg_ExpiryYear).fadeIn();
            return false;
        }
    }

    $('#JazzCashErrorDiv').hide();
    $('#JazzCashErrorDiv').html("");
    //document.getElementById("pp_CustomerCardExpiryM").value = "";
    //document.getElementById("pp_CustomerCardExpiryY").value = "";
    return true;
}

$(function () {

    renderPayaxisFields();

    $("#JazzCashFields").parents("form").bind("submit", function (e) {
        if ($('#DoProcess').val() == "1") {
            //console.log(new Date().getTime());
            //alert(e);
            e.preventDefault();
            $('#JazzCashErrorDiv').hide();
            $('#JazzCashErrorDiv').html("");
            var _formMethod = $("#JazzCashFields").parents("form").attr("method");
            var _formAction = $("#JazzCashFields").parents("form").attr("action");
            var formValidation = validatePayaxisCheckoutForm();
            if (formValidation) {
                setTimeout(function () {
                    var checkState = DoPay();
                }, 500)
            } else {
                console.log("validation failed");
            }
        } else {
            console.log("invalid bin entered");
            e.preventDefault();
        }
    });

    $(document).ajaxSend(function (event, jqXHR, settings) {
        //$('.LoaderWrapper').fadeIn();
        $('input[type="submit"]').prop('disabled', true).css('opacity', '0.5').css('cursor', 'not-allowed');
        $('button').prop('disabled', true).css('opacity', '0.5').css('cursor', 'not-allowed');
    });

    $(document).ajaxComplete(function (event, jqXHR, settings) {
        //$('.LoaderWrapper').fadeOut();
        $('input[type="submit"]').prop('disabled', false).css('opacity', '1').css('cursor', '');
        $('button').prop('disabled', false).css('opacity', '1').css('cursor', '');
    });

})

function processDoPay(checkState) {
    if (checkState[0] && checkState[1] == "CARD_ENROLLED") {
        $('#JazzCashErrorDiv').hide();
        $('#JazzCashErrorDiv').html("");
        if (isDiscountCalculated) {
            $("#JazzCashFields").parents("form").unbind("submit");
        }

        //mpopupWin = window.open('', 'popupWin' + Math.random(), 'width=400, height=400');
        //mpopupWin.document.write($("#stagingForm").html());
        //mpopupWin.document.forms[0].submit();

        $("#pp_masterCardResponse").html('<iframe id="pp3DsFrame" width=100% height=450></iframe>');
        $("#pp_masterCardResponse").before('<div class="pp_overlay_3ds"></div>');
        //$(".pp_overlay_3ds").html($('#pp_masterCardResponse').html());
        $("#pp_masterCardResponse").prepend('<p class="close_pp_overlay_3ds">X</p>').html();
        var _iframe = document.getElementById("pp3DsFrame");
        _iframe.contentWindow.contents = $("#stagingForm").html();

        _iframe.src = 'javascript:window["contents"]';
        setTimeout(function () {
            _iframe.contentWindow.document.forms[0].submit();
        }, 1000);
        //console.log(new Date().getTime());
        $('#submitBtnPayAxis').hide();
        //$('fieldset').hide()                    
        $('.close_pp_overlay_3ds').on('click', function (e) {
            $('.pp_overlay_3ds').hide();
            $('#pp_masterCardResponse').hide();
        });
        $('.cd-close').on('click', function (e) {
            e.preventDefault();
            return false;
        });
        $('.cd-overlay').on('click', function (e) {
            e.preventDefault();
            return false;
        });
        $(document).keyup(function (event) {
            if (event.which == '27') {
                e.preventDefault();
                return false;
            }
        });
        $('#pp_masterCardResponse').fadeIn();
        document.getElementById('pp_masterCardResponse').scrollIntoView(true);
    } else if (checkState[0] &&
        (checkState[1] == "CARD_NOT_ENROLLED" ||
            checkState[1] == "AUTHENTICATION_NOT_AVAILABLE")) {
        $('#JazzCashErrorDiv').hide();
        $('#JazzCashErrorDiv').html("");
        if (isDiscountCalculated) {
            $("#JazzCashFields").parents("form").unbind("submit");
            $("#JazzCashFields").parents("form").submit();
        }
    } else {
        console.log("Error occurred - 2"); // we have to show response any ways
        $("#JazzCashFields").parents("form").unbind("submit");
        $("#JazzCashFields").parents("form").submit();
    }
}

function ForceSubmit() {
    if (isDiscountCalculated) {
        $('form').submit();
    }
}
