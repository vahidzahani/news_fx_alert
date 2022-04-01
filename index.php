
<?php
#vahid.zahani@gmail.com - admin.projfa.ir
date_default_timezone_set('asia/tehran');

/**
 * Simple example of extending the SQLite3 class and changing the __construct
 * parameters, then using the open method to initialize the DB.
 */
class MyDB extends SQLite3
{
    function __construct()
    {
        $this->open('news.db');
    }
}
$db = new MyDB();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link rel="icon" href="https://www.shareicon.net/data/16x16/2016/03/28/466566_blue_24x24.png" type = "image/x-icon">
    <title>news fx</title>
</head>
<script>
    var mytimer=0;
    function playSound(url) {
        const audio = new Audio(url);
        audio.play();
    }
    //repeat play sound for actived alarm
    function fn_repeat_sound() {
        setInterval(() => {
            playmysound()
        }, 5000);
    }
    function playmysound() {
        playSound('ding.wav');
    }
</script>
<body>
    <h4>Now: <?php echo date("Y/m/d - H:i",time());?></h4>
    <form action="?">
        <input type="text" name="Y" size="1"  value="<?php echo date('Y',time());?>">
        <input type="text" name="M" size="1"  value="<?php echo date('m',time());?>">
        <input type="text" name="D" size="1"  value="<?php echo date('d',time());?>">
        <label for="">-</label>
        <input type="text" name="H" size="1"  value="<?php echo date('H',time());?>">
        <label for="">:</label>
        <input type="text" name="i" size="1"  value="<?php echo date('i',time());?>">
        <label for=""> Currency : </label>
        <input type="text" name="currency" size="1"  >
        <label for=""> impact : </label>
        <select name="impact" >
            <option value="1">1-Low</option>
            <option value="2">2-Medium</option>
            <option value="3">3-High</option>
        </select>
        <label for=""> Description : </label>
        <input type="text" name="description" size="10"  >
        <input type="submit" class="btn btn-success btn-sm">
        <a href="?" class="btn btn-warning btn-sm">Reload</a>
        <button class="btn btn-default btn-sm" onclick="playmysound();" type="button">Test 2 Play</button>
    </form>
    <a href="https://www.forexfactory.com/calendar" class="btn btn-link" target="_blank">Forexfactory</a>
    <a href="https://academywave.com/forex-economic-calendar/" class="btn btn-link" target="_blank">Academy</a>
    
    <?php
    
    if (isset($_REQUEST['del'])) {
        $q="delete from tbl_news where id=".$_REQUEST['del'];
        $news = $db->query($q);
    }
    if (isset($_REQUEST['description'])) {
        $_time=mktime($_REQUEST['H'], $_REQUEST['i'], 0, $_REQUEST['M'], $_REQUEST['D'], $_REQUEST['Y']);
        $q="INSERT INTO tbl_news (time, currency, description, impact) VALUES ($_time, '".$_REQUEST['currency']."', '".$_REQUEST['description']."', ".$_REQUEST['impact'].")";
        $news = $db->query($q);
    }
    ?>
        
    <table border="1" class="table table-bordered" >
        <thead>
        <tr><th>#</th><th>date and time</th><th>currency</th><th>description</th><th>impact</th><th>...</th></tr>
        </thead>
        <tbody>
            <?php
            $results = $db->query('SELECT * FROM tbl_news order by time desc');
            $counter=0;
            $min_of_timer=999999999;//this is timer for play sound alarm
            $arr = array("dark","light", "warning", "danger");
            $impact = array("","Low", "Medium", "High");
            while ($row = $results->fetchArray()) {
                $row_style_code=$row['impact'];
                if ($row['time']<time()) {
                    $row_style_code=0;
                }
                ?><tr class="table-<?php echo $arr[$row_style_code];?>">
                    <td><?php echo ++$counter; ?></td>
                    <?php $timer=(int)(($row['time']-time())/60);if($timer<$min_of_timer && $timer-5>0)$min_of_timer=$timer;?>
                    <td><?php echo date("Y/m/d - H:i",$row['time']);?> <b><?php echo $timer; ?> </b> minutes </td>
                    <td><?php echo $row['currency'];?></td>
                    <td><?php echo $row['description'];?></td>
                    <td><?php echo $impact[$row['impact']]."-".$row['impact'];?></td>
                    <td>
                        <a href="?del=<?php echo $row['id'];?>" class="btn btn-outline-danger btn-sm" style="padding-top: 0;padding-bottom: 0;"><i class="bi-trash"></i></a>
                        <a href="?" class="btn btn-outline-primary btn-sm" style="padding-top: 0;padding-bottom: 0;"><i class="bi-pencil-fill"></i></a>
                    </td>
                </tr><?php
            }
            ?>
        </tbody>
    </table>
    <script>
        mytimer=<?php echo $min_of_timer;?>;
        if (mytimer!=999999999) {
            mytimer=mytimer-5;
            mytimer=mytimer*60*1000;
            const myTimeout = setTimeout(fn_repeat_sound, mytimer);
            alert(mytimer +" seconds - " + mytimer/60000 + " minutes - " + ((mytimer/60000)/60).toFixed(2) + " Hours");
        }else{
            alert("there is no NEWS item");
        }
    </script>
</body>
</html>

