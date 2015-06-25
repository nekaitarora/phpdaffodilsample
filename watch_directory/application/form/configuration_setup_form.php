<form id="THEFORM" action="application/controller/save_config_wd.php" method="post">
<table class="options">
    
    <tr>
        <th style="width:50%">Set Up Configuration</th>
    </tr>
    <tr>
        <td>
            &nbsp;
        </td>
    </tr>
    <tr>
        <td>
            <fieldset>
                    <legend>Source Directory Path</legend>
                    <?php if ($inputlabel) { ?>
                            <input id=dir type=hidden value="<?php echo $dir; ?>">
                            <input id=path type="text" readonly="readonly" class="tablename" name="folderpath" value="<?php echo ($targetfile) ? $targetfile : $dir; ?>"
                            <?php if (!$secondchance) echo "readonly"; ?>
                            size="73" maxlength="256" onfocus="select()" class="input" />
                    <?php } else { ?>
                            <div style="width: 600px">&nbsp;</div>	
                            <input id=path type=hidden name="dir" value="<?php echo $dir; ?>">
                    <?php } ?>
            </fieldset>
            <fieldset>
                <legend>Select Source Directory</legend>
                <div style="float:left;">
                <!-- and the file table itself -->
                <table align="center" border="0" style="border:1px solid #999999; background-color:#F5F5F5;" cellpadding="1" cellspacing="3">
                <tr><td align="left" valign="top">
                        <?php if ($showfiles) { ?>
                            <table width="700" border="0" align="center" style="border:1px solid #999999;">
                                    <tr bgcolor="#E1EEF4" height="22">
                                            <td align="left">Name</td>
                                            <td align="center" width="80">Size</td>
                                            <td align="center" width="80">Permission</td>
                                            <td align="center" width="150">Date Modified</td>
                                    </tr>
                            </table>
                            <?php
                                    $maxlines = $ischild ? 8 : 15;
                                    $verticalsize = $maxlines * $lineheight + 2;  // 2px of cellpadding for the empty <thead>
                            ?>

                            <?php if ($body_count < $maxlines) {		/* no scollbar */ ?>
                                    <table width="700" border="0" align="center" style="border:1px solid #999999;">
                                    <thead><td width="80"></td><td width="80"></td><td width="150"></td></thead>
                                            <?php echo $body; ?>
                                    </table>
                            <?php } else { ?>
                                    <div style="height: <?php echo $verticalsize; ?>px; width: 700px; margin-left: auto ; margin-right: auto ;overflow: auto;">
                                            <table width="682" border="0" align="center" style="border:1px solid #999999;">
                                            
                                                    <?php echo $body; ?>
                                            </table>
                                    </div>
                            <?php } ?>
                    <?php } ?>
                </td></tr>
                </table>
                </div>
                <div style="padding-top: 10px;">
                </div>
            </fieldset>
            <br/>
            <fieldset>
                    <legend>Destination Directory Path</legend>
                    <?php if ($inputlabel2) { ?>
                            <input id=dir type=hidden value="<?php echo $dir2; ?>">
                            <input id=path type="text" readonly="readonly" class="tablename" name="destfolderpath" value="<?php echo ($targetfile2) ? $targetfile2 : $dir2; ?>"
                            <?php if (!$secondchance2) echo "readonly"; ?>
                            size="73" maxlength="256" onfocus="select()" class="input" />
                    <?php } else { ?>
                            <div style="width: 600px">&nbsp;</div>	
                            <input id=path type=hidden name="dir" value="<?php echo $dir2; ?>">
                    <?php } ?>
            </fieldset>
            <fieldset>
                <legend>Select Source Directory</legend>
                <div style="float:left;">
                <!-- and the file table itself -->
                <table align="center" border="0" style="border:1px solid #999999; background-color:#F5F5F5;" cellpadding="1" cellspacing="3">
                <tr><td align="left" valign="top">
                        <?php if ($showfiles2) { ?>
                            <table width="700" border="0" align="center" style="border:1px solid #999999;">
                                    <tr bgcolor="#E1EEF4" height="22">
                                            <td align="left">Name</td>
                                            <td align="center" width="80">Size</td>
                                            <td align="center" width="80">Permission</td>
                                            <td align="center" width="150">Date Modified</td>
                                    </tr>
                            </table>
                            <?php
                                    $maxlines2 = $ischild2 ? 8 : 15;
                                    $verticalsize2 = $maxlines2 * $lineheight + 2;  // 2px of cellpadding for the empty <thead>
                            ?>
                            <?php if ($body_count2 < $maxlines2) {		/* no scollbar */ ?>
                                    <table width="700" border="0" align="center" style="border:1px solid #999999;">
                                    <thead><td width="80"></td><td width="80"></td><td width="150"></td></thead>
                                            <?php echo $body2; ?>
                                    </table>
                            <?php } else { ?>
                                    <div style="height: <?php echo $verticalsize2; ?>px; width: 700px; margin-left: auto ; margin-right: auto ;overflow: auto;">
                                            <table width="682" border="0" align="center" style="border:1px solid #999999;">
                                            
                                                    <?php echo $body2; ?>
                                            </table>
                                    </div>
                            <?php } ?>
                    <?php } ?>
                </td></tr>
                </table>
                </div>
                <div style="padding-top: 10px;">
                </div>
            </fieldset>
        </td>
    </tr>
    <tr>
        <td>
            <br/>
            <fieldset>
                    <legend>File Converse Configuration</legend>
                    <?php echo $selOpt; ?>
                    <div style="padding-top: 10px;">
                    </div>
            </fieldset>
            <fieldset>
                    <div style="padding-top: 10px;">
                    </div>
                    <div style="padding-top: 10px; padding-left: 315px;">
                        <input type="submit" value="Save Schedule" />
                    </div>
                    <div style="padding-top: 10px;">
                    </div>
            </fieldset>
        </td>
    </tr>
</table>
</form>
<script type="text/javascript">
    $(function(){
        $('#THEFORM').form({
            success:function(data){
                    alert(data);
            }
        });
    });
</script>