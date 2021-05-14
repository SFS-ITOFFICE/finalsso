
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>SFS </title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=9">

    <!-- Bootstrap -->

    <link href="https://hrdb.sfs.or.kr/bs3/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://hrdb.sfs.or.kr/bs3/css/font-awesome.min.css" rel="stylesheet">

    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script type="text/javascript" src="https://hrdb.sfs.or.kr/bs3/js/bootstrap.min.js"></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style type="text/css">
        .modal-footer {   border-top: 0px; }
    </style>
</head>

<!-- HTML code from Bootply.com editor -->
<body>
<!--login modal-->
<div id="loginModal" class="modal show" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="text-center"><img src="https://ws.sfs.or.kr/img/sfs_logo.png" height="130"></h1>
            </div>

            <div class="modal-body">
                @if ($errors->has('message'))
                    <span class="help-block">
                        <p class='text-danger'><strong>{{ $errors->first('message') }}</strong></p>
                        </span>
                @endif
                <form class="form col-md-12 center-block" action="{{ route('adauth.store') }}" method="post" name="frmLogin" id="frmLogin">
                    @csrf
                    @samlidp
                    <div class="form-group">SFS ID
                        <div class="input-group  input-group-lg {{ $errors->has('password') ? 'has-error' : '' }}">
                            <input type="text" class="form-control" name="loginid" id="loginid"  value="{{ old('email') }}" placeholder="" aria-describedby="basic-addon2">
                            <span class="input-group-addon" id="basic-addon2">@seoulforeign.org</span>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                        Password <input type="password" class="form-control input-lg" name="loginpw" id="loginpw" placeholder="">
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary btn-lg btn-block" type="submit" id="regularsubmit">Sign in</button>
                    </div>
                    <input type="hidden" name="returl" value="{{ $returl }}">
                    <input type="hidden" name="from" value="{{ $from }}">
                </form>
            </div>

            <div class="modal-footer">
                <div class="col-md-12">
                    <!--					<button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>-->
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function() {

        jQuery('#loginid').focus();

    });
</script>
</body>
</html>

