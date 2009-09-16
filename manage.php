<?php
session_start();
if(!isset($_SESSION['user']))
	header('Location: login.php');
$manageid=$_SESSION['user_id'];
?>

<html>
<head>
<script type='text/javascript' src='javascripts/prototype.js'></script>
<script type='text/javascript'>

function login(dropName,dropId) {
	new Ajax.Request('logdrop.php',
	{
		method:'post',
		postBody:'dropname='+dropName+'&dropid='+dropId,
		onSuccess: function(log) {
			var dropLink = log.responseText;
			window.open(dropLink);
			}
	});
}

function openPlayer(dropName) {
	window.open('player.html?drop='+dropName,"dropPlayer","height=250","status=0","toolbar=0","directories=0","scrollbars=1","location=1");
}

function deleteOptions(deleteName,deleteId) {
	if(confirm('Are you sure you want to remove this Drop from your list?'))
	{
		if(confirm("Do you also wish to PERMANENTLY destroy this Drop and it\'s contents on Drop.io?\n\nIf you only wish to remove this Drop from your list, click Cancel to continue.\nOtherwise, hit OK to destroy the Drop."))
		{
			destroyDrop(deleteName,deleteId);
		} else {
			deleteDrop(deleteName,deleteId);
		}
	}
}

function destroyDrop(deleteName,deleteId) {
	new Ajax.Request('destroy.php',
	{
		method:'get',
		parameters: {dropname: deleteName, dropid: deleteId},
		onSuccess: function(transport){
			var str = transport.responseText.split(' ');
			var deleteName = str[0];
			var deleteId = str[1];
			$('junk').replace('<div id=junk>'+transport.responseText+'</div>');
			document.getElementsByTagName('message')[0].id='apiDestroy';
			if(($('apiDestroy').innerHTML) == 'The drop was destroyed.')
			{
				$('junk').replace('<div id=junk>Nothing to see here people. Move along. Seriously.</div>');
				deleteDrop(deleteName,deleteId);
			} else {
				alert($('apiDestroy').innerHTML);
			};
		},
		onFailure: function(){ alert('Something has failed') }
	});
}

function deleteDrop(deleteName,deleteId) {
	new Ajax.Request('delete.php',
	{
		method:'post',
		postBody:'dropname='+deleteName+'&dropid='+deleteId,
		onSuccess: function(foo){
			$('drop_'+deleteId).remove();
		}
	});
}

function addDrop() {
	var addName = $('addName').value;
	var addToken = $('addToken').value;
	$('addName').value = '';
	$('addToken').value = '';
	if((addName != '') && (addToken != ''))
	{
		new Ajax.Request('getdrop.php',
		{
			method:'post',
			postBody:'dropname='+addName+'&token='+addToken,
			onSuccess: function(add){
				$('junk').replace('<div id=junk>'+add.responseText+'</div>');
				//Check for message tag sent by API, either invalid name or token given
				if(document.getElementsByTagName('message')[0])
				{
					alert(document.getElementsByTagName('message')[0].innerHTML);
				} else {
					document.getElementsByTagName('admin_token')[0].id='getToken';
					var getToken = $('getToken').innerHTML;
					if(getToken == 'dupeDrop')
					{
						alert('This Drop is a duplicate.\n\nDrop will not be entered in your list again.');
						$('junk').replace('<div id=junk>Nothing to see here people. Move along. Seriously.</div>');
					} else {
						insertDrop(addName,getToken);
					}
				}
			},
			onFailure: function() { alert('Something has failed'); }
		});
	} else {
		alert('At least one field is empty');
	}
}

function insertDrop(insertName,insertToken) {
	new Ajax.Request('insert.php',
	{
		method:'post',
		postBody:'dropname='+insertName+'&token='+insertToken,
		onSuccess: function(insert) {
			var str = insert.responseText.split(' ');
			if(str[0] == "dropInserted")
			{
				alert('Drop '+insertName+' has been added');
				$('droplistul').insert("<li id=drop_"+str[1]+"><span><a href=\"javascript: void(0)\" onClick=\"javascript: login('"+insertName+"','"+str[1]+"')\">Drop "+insertName+"</a></span><a title='Drop Audio Player' href='#' onClick=\"javascript: openPlayer('"+insertName+"')\"><img src='/images/audioplayer.png' border=0/></a> <a href='#' onClick=\"javascript: deleteOptions('"+insertName+"','"+str[1]+"')\"><img border=0 src=/images/delete.png></img></a></li>");
				$('junk').replace('<div id=junk>Nothing to see here people. Move along. Seriously.</div>');
			}
		},
		onFailure: function() {
			alert('Something has failed');
		}
	});
}

function createDrop() {
	var createName = $('createName').value;
	var createAdmin = $('createAdmin').value;
	var createPassword = $('createPassword').value;
	var createPremium = $('createPremium').value;
	var createExpire = $('createExpire').value;
	var guestAdd = $('gca').checked;
	var guestComment = $('gcc').checked;
	var guestDelete = $('gcd').checked;
	$('createSubmit').disabled=true;
	$('createSubmit').value='Creating Drop...';

	new Ajax.Request('createdrop.php',
	{
		method:'post',
		postBody:'name='+createName+'&admin_password='+createAdmin+'&password='+createPassword+'&premium_code='+createPremium+'&expiration_length='+createExpire+'&guests_can_add='+guestAdd+'&guests_can_comment='+guestComment+'&guests_can_delete='+guestDelete,
		onSuccess: function(create) {
			$('junk').replace('<div id=junk>'+create.responseText+'</div>');
			document.getElementsByTagName('admin_token')[0].id='apiToken';
			document.getElementsByTagName('name')[0].id='apiName';
			var apiToken = $('apiToken').innerHTML;
			var apiName = $('apiName').innerHTML;
			insertDrop(apiName,apiToken);
			$('createName').value = '';
			$('createAdmin').value = '';
			$('createPassword').value = '';
			$('createPremium').value = '';
			$('createSubmit').value='Create Drop';
			$('createSubmit').enable();
		},
		onFailure: function() {
			alert('Something has failed');
			$('createSubmit').value='Create Drop';
			$('createSumbit').enable();
		}
	});
}

</script>
<link rel='stylesheet' type='text/css' href='drop.css'/>
<title>myDrops Page</title>
</head>
<body>
<h1>myDrops Page</h1>
<div id='logout'>
<?php echo $_SESSION['user']."<br>" ?>
<a href='control.php'>Control Panel</a><br>
<a href='logout.php'>Logout</a><br>
</div>

<?php
include 'config.php';

$con = mysql_connect($mysqlserver,$mysqluser,$mysqlpass);
if (!$con)
        {
        die('Could not connect:'.mysql_error());
        }
mysql_select_db($mysqldb,$con);
$result = mysql_query("SELECT id,name FROM drops WHERE user_id='$manageid' ORDER BY name ASC");

$drops=array();
$dropids=array();
while($lines=mysql_fetch_array($result, MYSQL_ASSOC))
{
	if (isset($lines['name']) && (isset($lines['id'])))
	{
		array_push($drops,$lines['name']);
		array_push($dropids,$lines['id']);
	}
}
?>

<div id='dropBar'>
<h3>Your Drops <sub title='This is your list of Drops. By clicking the name of your Drop, you can log into that Drop as the admin. You can also play audio files from your Drops, delete them from your list, or destroy them completely.'>?</sub></h3>
<div id='droplist'>
<ul id='droplistul'>
<?php
$q = count($drops);
for ($i=0; $i<$q; $i++)
        {
        $disp_count=$i+1;

	echo ("<li id='drop_$dropids[$i]'>
	<span><a href=\"javascript: void(0)\" onClick=\"javascript: login('$drops[$i]','$dropids[$i]')\">Drop $drops[$i]</a></span>
	<a class='audioPlayerLink' title='Drop Audio Player' href='#' onClick=\"javascript: openPlayer('$drops[$i]')\"><img src='/images/audioplayer.png' border=0/></a> ");
	echo("<a href='#' onClick=\"javascript: deleteOptions('$drops[$i]','$dropids[$i]')\"><img border=0 src=/images/delete.png></img></a></li>");
	}
?>

</ul>
</div>
</div>

<div id='manageForms'>
<br>
<fieldset>
<legend>Add a Drop to the List <sub title='Add a Drop to your list. If nothing happens, then a field was not provided or incorrect. Roll over a text field for more info.'>?</sub></legend>
<form method="post" onSubmit="return false">
<span>Drop Name: </span>
<input id='addName' title='The name of your Drop' type="text" name="dropname" /><br>
<span>Drop Admin Token/Password: </span>
<input id='addToken' title="Your admin token is found under Settings >> Drop Details or at http://drop.io/YOUR_DROP/admin/details/" type="password" name="token" /><br>
<input class='manageSubmit' type="submit" value="Add Drop" onClick="addDrop()"/>
</form>
</fieldset>
<br>
<fieldset style='padding-bottom: 32px'>
<legend>Create a Drop <sub title='Create a Drop and add it to your list. If fields are empty, then a randomized Drop will be created for you. Roll over a text field for more info.'>?</sub></legend>
<form method='post' onSubmit="return false">
<span>Drop Name (Optional): </span>
<input id='createName' title="Valid characters are 'a-z' 'A-Z' '0-9' and '_'. Name should be at least 7 characters long." type='text' name='name' /><br>
<span>Admin Password (Optional):</span>
<input id='createAdmin' title='Administrative password for yourself' type='password' name='admin_password' /><br>
<span>Guest Password (Optional): </span>
<input id='createPassword' title='Password for your guests to use' type='password' name='password' /><br>
<span>Premium Code (Optional): </span>
<input id='createPremium' title='Premium Upgrade Codes available for purchase at drop.io/upgrade' type='password' name='premium_code' /><br>

<span>Expiration Length: </span>
<select id='createExpire' title='Set the time that your Drop expires' name='expiration_length' class='manageSubmit'>
	<option value='1_DAY_FROM_NOW'>1 Day From Now</option>
	<option value='1_WEEK_FROM_NOW'>1 Week From Now</option>
	<option value='1_MONTH_FROM_NOW'>1 Month From Now</option>
	<option value='1_YEAR_FROM_NOW'>1 Year From Now</option>
	<option value='1_DAY_FROM_LAST_VIEW'>1 Day From Last View</option>
	<option SELECTED value='1_WEEK_FROM_LAST_VIEW'>1 Week From Last View</option>
	<option value='1_MONTH_FROM_LAST_VIEW'>1 Month From Last View</option>
	<option value='1_YEAR_FROM_LAST_VIEW'>1 Year From Last View</option>
</select><br><br>
<span>Guests Can Add: </span><input id='gca' class='manageSubmit' type='checkbox' name='guests_can_add' checked/><br>
<span>Guests Can Comment: </span><input id='gcc' class='manageSubmit' type='checkbox' name='guests_can_comment' checked /><br>
<span>Guests Can Delete: </span><input id='gcd' class='manageSubmit' type='checkbox' name='guests_can_delete' checked /><br>
<input id='createSubmit' style='margin-top: 10px' class='manageSubmit' type='submit' value='Create Drop' onClick="createDrop()" ondblclick="spam()" />
</form>
</fieldset>
</div>

<div id='junk'>Nothing to see here people. Move along. Seriously.</div>

</body></html>
