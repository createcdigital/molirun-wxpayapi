<?php

    // include_once '../../db/connect.php';

    // // for debug
    // $data = array(
    //     'openid' => 'o1zitjlK5QY7rH113wDe2f96ThUtOw',
    //     'attach' => '13564137185',
    //     'out_trade_no' => '131507280120160312101252',
    //     'transaction_id' => '1008080181201603184071193462',
    // );
    $phone         = $data['attach'];
    $openid        = $data['openid'];

    $errormsg = "";
    if(isset($phone) && isset($openid))
    {
        if ($stmt = $mysqli->prepare("SELECT grouptype, teesize, packagetype FROM user WHERE phone=? and paystatus=1 and openid=?")) {

            // Bind the variables to the parameter as strings.
            $stmt->bind_param("ss", $phone, $openid);

            /* execute query */
            $stmt->execute();

            /* bind result variables */
            $stmt->bind_result($grouptype, $teesize, $packagetype);

            /* fetch values */
            while ($stmt->fetch()) {
                 $grouptype   = $grouptype;
                 $teesize     = $teesize;
                 $packagetype = $packagetype;
             }

             if(isset($grouptype) && isset($teesize) && isset($packagetype))
             {
                if ($stmt1 = $mysqli->prepare("SELECT fivekms, tenkms, xssize, ssize, msize, lsize, xlsize, xxlsize, generalpackage, seniorpackage FROM stock")) {
                    /* execute query */
                    $stmt1->execute();

                    /* bind result variables */
                    $stmt1->bind_result($fivekms, $tenkms, $xssize, $ssize, $msize, $lsize, $xlsize, $xxlsize, $generalpackage, $seniorpackage);

                    /* fetch values */
                    while ($stmt1->fetch()) {
                         $fivekms        = $fivekms;
                         $tenkms         = $tenkms;
                         $xssize         = $xssize;
                         $ssize          = $ssize;
                         $msize          = $msize;
                         $lsize          = $lsize;
                         $xlsize         = $xlsize;
                         $xxlsize        = $xxlsize;
                         $generalpackage = $generalpackage;
                         $seniorpackage  = $seniorpackage;
                    }

                    if(isset($fivekms) && isset($tenkms) && isset($xssize) && isset($ssize) && isset($msize) && isset($lsize) && isset($xlsize) && isset($xxlsize) && isset($generalpackage) && isset($seniorpackage))
                    {

                        // update stock
                        if($grouptype === 5)
                            $fivekms--;
                        else if($grouptype === 10)
                            $tenkms--;

                        if($teesize === "XS")
                            $xssize--;
                        else if($teesize === "S")
                            $ssize--;
                        else if($teesize === "M")
                            $msize--;
                        else if($teesize === "L")
                            $lsize--;
                        else if($teesize === "XL")
                            $xlsize--;
                        else if($teesize === "XXL")
                            $xxlsize--;

                        if($packagetype === 0)
                            $generalpackage--;
                        else if($packagetype === 1)
                            $seniorpackage--;

                        if ($stmt2 = $mysqli->prepare("UPDATE stock SET fivekms=?, tenkms=?, xssize=?, ssize=?, msize=?, lsize=?, xlsize=?, xxlsize=?, generalpackage=?, seniorpackage=?")) {

                            // Bind the variables to the parameter as strings.
                            $stmt2->bind_param("ssssssssss", $fivekms, $tenkms, $xssize, $ssize, $msize, $lsize, $xlsize, $xxlsize, $generalpackage, $seniorpackage);

                            /* execute query */
                            if($stmt2->execute())
                                Log::DEBUG("notify to db-stock: success! openid:".$openid);
                            else
                            {
                                $errormsg = '准备预执行T-SQL脚本发生错误！';
                            }

                            $stmt2->close();
                        }else
                        {
                            $errormsg = '库存字段数据为空！';
                        }
                    }else
                    {
                        $errormsg = '准备预执行T-SQL脚本发生错误！';
                    }
                }else
                {
                    $errormsg = '查询数据库stock为空！';
                }

                $stmt1->close();
             }else
            {
                $errormsg = '根据phone查询的数据为空！';
            }

        }else
        {
            $errormsg = '准备预执行T-SQL脚本发生错误！';
        }

        $stmt->close();
    }else
    {
        $errormsg = '请求参数phone不能为空!';
    }


    if($errormsg !== "")
        Log::DEBUG("notify to db-stock: fail! openid:".$openid.", description:".$errormsg);

    /* close connection */
    $mysqli->close();
?>