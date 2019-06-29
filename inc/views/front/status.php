<!-- TF!WP_status_change_result : <?php echo $this->message ?> -->
<p>
<?php 
if (substr($this->message, 0, 2) == 'OK') {
	_e('OK ! Task has been set to ', 'tasks');
} else {
	_e('Sorry, an error has occured.', 'tasks');
}
echo ' <b>'.substr($this->message, 4).'.</b>'; 
?>
</p>
