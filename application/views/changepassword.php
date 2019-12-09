<!DOCTYPE html>
<html>

    <head>

        <meta charset="UTF-8">

        <title>CodePen - Log-in</title>

        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    </head>

    <body>
        <form action="<?= base_url('Welcome/changepassword') ?>" method="post">
            <div class="col-md-4 col-md-offset-4">
                <h2>Change Password</h2>
                <br> 
                <input class="form-control" type="password" placeholder="New Password" name="password" id="password"/></br>
                <input class="form-control" type="password" Placeholder="Confirm New Password" name="confirmpassword" id="cpassword"/><br><br>
                <input type="button" class="btn btn-primary" value="Change Password" id="submit"/>
                <input type="hidden" name='id' value="<?php echo $this->uri->segment(3) ?>" id="id"/>
                <input type="hidden" name='token' value="<?php echo $this->uri->segment(4) ?>"/>
            </div>
        </form>

    </body>
    <script>
        $("#submit").click(function () {
            password=$('#password').val();
            cpassword=$('#cpassword').val();
            if(password=='' || cpassword=='')
            {
                alert("Password and confirm password can't be empty");
                return false;
            }
            id=$('#id').val();
            
            $.ajax({url: "<?php echo base_url('index.php/Welcome/changepassword'); ?>", type: 'POST',
                data: {"password": password, "cpassword": cpassword,"id":id},
                success: function (result) {
                    alert(result);
                }});
        });
        prototype</script>
</html>