<?php
$states = array(
    'AL' => 'ALABAMA',
    'AK' => 'ALASKA',
    'AS' => 'AMERICAN SAMOA',
    'AZ' => 'ARIZONA',
    'AR' => 'ARKANSAS',
    'CA' => 'CALIFORNIA',
    'CO' => 'COLORADO',
    'CT' => 'CONNECTICUT',
    'DE' => 'DELAWARE',
    'DC' => 'DISTRICT OF COLUMBIA',
    'FM' => 'FEDERATED STATES OF MICRONESIA',
    'FL' => 'FLORIDA',
    'GA' => 'GEORGIA',
    'GU' => 'GUAM GU',
    'HI' => 'HAWAII',
    'ID' => 'IDAHO',
    'IL' => 'ILLINOIS',
    'IN' => 'INDIANA',
    'IA' => 'IOWA',
    'KS' => 'KANSAS',
    'KY' => 'KENTUCKY',
    'LA' => 'LOUISIANA',
    'ME' => 'MAINE',
    'MH' => 'MARSHALL ISLANDS',
    'MD' => 'MARYLAND',
    'MA' => 'MASSACHUSETTS',
    'MI' => 'MICHIGAN',
    'MN' => 'MINNESOTA',
    'MS' => 'MISSISSIPPI',
    'MO' => 'MISSOURI',
    'MT' => 'MONTANA',
    'NE' => 'NEBRASKA',
    'NV' => 'NEVADA',
    'NH' => 'NEW HAMPSHIRE',
    'NJ' => 'NEW JERSEY',
    'NM' => 'NEW MEXICO',
    'NY' => 'NEW YORK',
    'NC' => 'NORTH CAROLINA',
    'ND' => 'NORTH DAKOTA',
    'MP' => 'NORTHERN MARIANA ISLANDS',
    'OH' => 'OHIO',
    'OK' => 'OKLAHOMA',
    'OR' => 'OREGON',
    'PW' => 'PALAU',
    'PA' => 'PENNSYLVANIA',
    'PR' => 'PUERTO RICO',
    'RI' => 'RHODE ISLAND',
    'SC' => 'SOUTH CAROLINA',
    'SD' => 'SOUTH DAKOTA',
    'TN' => 'TENNESSEE',
    'TX' => 'TEXAS',
    'UT' => 'UTAH',
    'VT' => 'VERMONT',
    'VI' => 'VIRGIN ISLANDS',
    'VA' => 'VIRGINIA',
    'WA' => 'WASHINGTON',
    'WV' => 'WEST VIRGINIA',
    'WI' => 'WISCONSIN',
    'WY' => 'WYOMING',
    'AE' => 'ARMED FORCES AFRICA \ CANADA \ EUROPE \ MIDDLE EAST',
    'AA' => 'ARMED FORCES AMERICA (EXCEPT CANADA)',
    'AP' => 'ARMED FORCES PACIFIC'
);;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Balance Collect | Payment</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="noindex">      <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.4.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://www.google.com/recaptcha/api.js"></script>
    <style>
        .border-danger {

        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="card col-12 mx-auto card-outline" id="login-auth-container" style="display:none;"></div>
    <div class="row">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="col-md-12 mt-3 mb-3" id="merchant-heading">
                        <h1>Heading</h1>
                    </div>
                </div>
            </div>
        </div>
        <div style="width: 100%;border-top:2px solid black"></div>
        <div class="container">
            <div id="balances-container" class="row" style="display:none;"></div>
            <div id="cardholder-info-container" class="row">
                <div class="alert alert-success d-none" role="alert" id="success">
                    <h4 class="alert-heading">Success!</h4>
                    <p>The transaction is successfully completed</p>
                    <hr>
                    <p class="mb-0">Here is the transaction id:
                        <cite id="transaction-id"></cite>
                    </p>
                </div>
                <div class="alert alert-danger d-none" role="alert" id="error">
                    <p>The transaction failed</p>
                    <hr>
                    <p class="mb-0">
                    <cite id="error-message"></cite>
                    </p>
                </div>
                <form class="col-12" id="payment-form" method="post">
                    <div class="white-background">

                        <!--                        Patient Information-->
                        <div class="form-group mt-2">
                            <h3 class="text-primary">Patient Information</h3>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label" for="c-first-name">Patient First Name *</label>
                                    <input type="text" name="customer_first_name" id="c-first-name" class="form-control"
                                           required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="c-last-name">Patient Last Name *</label>
                                    <input type="text" name="customer_last_name" id="c-last-name" class="form-control"
                                           required>
                                </div>
                            </div>
                        </div>

                        <!--                        Payment Amount-->
                        <div class="form-group mt-2">
                            <h3 class="text-primary">Payment Amount</h3>
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label" for="amount">Amount *</label>
                                    <input type="number" min="1" step="1" name="amount" id="amount" class="form-control"
                                           required>
                                </div>
                            </div>
                        </div>

                        <!--                        Billing Information-->
                        <div class="form-group mt-2">
                            <h3 class="text-primary">Billing Information</h3>
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label" for="cc-number">Credit Card Number *</label>
                                    <input type="text" name="cc_number" id="cc-number" class="form-control"
                                           maxlength="16" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label" for="exp-year">Expiration Year *</label>
                                    <select name="exp_year" id="exp-year" class="form-control" required>
                                        <option selected disabled>Year</option>
                                        <?php
                                        for ($year = intval(date('Y')); $year < intval(date('Y')) + 5; $year++) {
                                            ?>
                                            <option><?php echo $year; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label" for="exp-month">Expiration Month *</label>
                                    <select name="exp_month" id="exp-month" class="form-control" required>
                                        <option selected disabled>Month</option>
                                        <?php
                                        for ($month = 1; $month < 13; $month++) {
                                            ?>
                                            <option value="<?php echo $month; ?>"><?php echo str_pad($month, 2, 0, STR_PAD_LEFT) . ' - ' . DateTime::createFromFormat('!m', $month)->format('F'); ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label" for="cc-code">CVV *</label>
                                    <input type="text" name="card_code" id="cc-code" class="form-control"
                                           maxlength="4" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="first-name">First Name *</label>
                                    <input type="text" name="first_name" id="first-name" class="form-control"
                                           required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="last-name">Last Name *</label>
                                    <input type="text" name="last_name" id="last-name" class="form-control"
                                           required>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label" for="street">Street Address *</label>
                                    <input type="text" name="street" id="street" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="city">City *</label>
                                    <input type="text" name="city" id="city" class="form-control" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label" for="state">State *</label>
                                    <select name="state" id="state" class="form-control" required>
                                        <option selected disabled>State</option>
                                        <?php
                                        foreach ($states as $state => $value) {
                                            ?>
                                            <option><?php echo $state; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label" for="zip">Zip Code *</label>
                                    <input type="number" maxlength="5" name="zip" id="zip" class="form-control"
                                           required>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label" for="email">Email *</label>
                                    <input type="text" name="email" id="email" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-2">
                            <div class="row">
                                <div class="offset-md-10 col-md-2 float-right">
                                    <button type="submit" id="process-bc" class="form-control btn-primary g-recaptcha"
                                            data-sitekey="SITE_KEY"
                                            data-callback='onSubmit' data-action='submit'>
                                        Submit
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="mb-5"></div>
    <div class="mb-5"></div>
    <div class="mb-5"></div>
    <script>
        function onSubmit(token) {
            if (validate() || 1) {
                let data = $("#payment-form").serialize();
                $.ajax({
                    url: 'index.php',
                    method: 'post',
                    data: data,
                    dataType: 'json',
                    success: function (resp) {
                        if (resp["transaction_id"] !== undefined) {
                            document.getElementById("payment-form").reset();
                            $('#transaction-id').html(resp["transaction_id"]);
                            $('#error').addClass('d-none');
                            $('#success').removeClass('d-none');
                            window.scrollTo(0, 0);
                        } else if (resp["error_message"] !== undefined) {
                            $('#error-message').html(resp["error_message"]);
                            $('#success').addClass('d-none');
                            $('#error').removeClass('d-none');
                        } else {
                            $('#error-message').html('Transaction failed');
                            $('#success').addClass('d-none');
                            $('#error').removeClass('d-none');
                        }
                    }
                });
            }
        }

        const validate = () => {
            const fields = [
                'c-first-name',
                'c-last-name',
                'cc-number',
                'exp-year',
                'exp-month',
                'cc-code',
                'first-name',
                'last-name',
                'street',
                'city',
                'state',
                'zip',
                'email',
                'amount',
            ];
            let valid = true;
            for (const fieldsKey in fields) {
                let input = $("#" + fields[fieldsKey]);
                if (!input.val()) {
                    input.addClass("border-danger");
                    valid = false
                } else {
                    input.removeClass("border-danger");
                }
            }

            return valid;
        }
    </script>
</body>
</html>