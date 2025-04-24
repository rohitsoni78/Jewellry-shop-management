<?php include 'admin/db_connect.php' ?>
<style type="text/css">
	.img-field {
		width: 25%;
		max-height: 25vh;
		overflow: hidden;
		display: flex;
		justify-content: center;
	}
	.detail-field {
		width: 50%;
	}
	.amount-field {
		width: 25%;
		text-align: right;
		display: flex;
		align-items: center;
		justify-content: center;
	}
	.img-field img {
		max-width: 100%;
		max-height: 100%;
	}
	.qty-input {
		width: 75px;
		text-align: center;
	}
	input::-webkit-outer-spin-button,
	input::-webkit-inner-spin-button {
		-webkit-appearance: none;
		margin: 0;
	}
</style>

<div class="col-lg-12">
<?php 
$qry = $conn->query("SELECT c.*,p.item_code,p.name as pname FROM cart c INNER JOIN products p ON p.id = c.product_id WHERE c.user_id = {$_SESSION['login_id']}");
$total = 0;
?>
<div class="row">
<div class="col-md-8">
<?php if($qry->num_rows > 0): ?>
	<ul class="list-group">
	<?php while($row = $qry->fetch_array()): 
		$total += $row['qty'] * $row['price'];
		$size = $conn->query("SELECT * FROM sizes WHERE id = {$row['size_id']}");
		$size = $size->num_rows > 0 ? $size->fetch_array()['size'] : 'N/A';
		$colour = $conn->query("SELECT * FROM colours WHERE id = {$row['colour_id']}");
		$colour = $colour->num_rows > 0 ? $colour->fetch_array()['color'] : 'N/A';
		$img = [];
		if (isset($row['item_code']) && !empty($row['item_code'])):
			if (is_dir('assets/uploads/products/'.$row['item_code'])):
				$_fs = scandir('assets/uploads/products/'.$row['item_code']);
				foreach($_fs as $v):
					if (is_file('assets/uploads/products/'.$row['item_code'].'/'.$v) && !in_array($v, ['.','..'])):
						$img[] = 'assets/uploads/products/'.$row['item_code'].'/'.$v;
					endif;
				endforeach;
			endif;
		endif;
	?>
	<li class="list-group-item" data-id="<?php echo $row['id'] ?>" data-price="<?php echo $row['price'] ?>">
		<div class="d-flex w-100">
			<div class="img-field mr-4 img-thumbnail rounded">
				<img src="<?php echo isset($img[0]) ? $img[0] : '' ?>" alt="" class="img-fluid rounded">
			</div>
			<div class="detail-field">
				<p>Product Name: <b><?php echo $row['pname'] ?></b></p>
				<p>Price: <b>₹<?php echo number_format($row['price'],2) ?></b></p>
				<p>Size: <b><?php echo $size ?></b></p>
				<p>Color: <b><?php echo $colour ?></b></p>
				<div class="d-flex col-sm-5">
					<span class="btn btn-sm btn-info btn-flat btn-minus"><i class="fa fa-minus"></i></span>
					<input type="number" class="form-control form-control-sm qty-input" value="<?php echo $row['qty'] ?>">
					<span class="btn btn-sm btn-info btn-flat btn-plus"><i class="fa fa-plus"></i></span>
				</div>
			</div>
			<div class="amount-field">
				<b class="amount">₹<?php echo number_format($row['qty'] * $row['price'], 2) ?></b>
			</div>
			<span class="float-right">
				<button class="btn btn-sm btn-outline-danger rem_item" type="button" data-id="<?php echo $row['id'] ?>"><i class="fa fa-trash"></i></button>
			</span>
		</div>
	</li>
	<?php endwhile; ?>
	</ul>
<?php else: ?>
	<center><b>No Item</b></center>
<?php endif; ?>
</div>

<div class="col-md-4">
	<div class="card mb-4">
		<div class="card-header bg-primary text-white"><b>Total Amount</b></div>
		<div class="card-body">
			<h4 class="text-right"><b id="tamount">₹<?php echo number_format($total,2) ?></b></h4>
		</div>
	</div>
	<button class="btn btn-block btn-primary" id="checkout" name="checkout" type="button">Checkout</button>
</div>
</div>
</div>

<script>
$(document).ready(function(){
	function calc() {
		let total = 0;
		$('.qty-input').each(function() {
			let li = $(this).closest('li');
			let price = parseFloat(li.attr('data-price'));
			let qty = parseInt($(this).val()) || 1;
			let amount = qty * price;
			li.find('.amount').text('₹' + amount.toLocaleString('en-US', { minimumFractionDigits: 2 }));
			total += amount;
		});
		$('#tamount').text('₹' + total.toLocaleString('en-US', { minimumFractionDigits: 2 }));
	}

	$('.btn-minus').click(function() {
		let input = $(this).siblings('input');
		let qty = Math.max(1, parseInt(input.val()) - 1);
		let id = $(this).closest('li').data('id');
		updateQty(id, qty, input);
	});

	$('.btn-plus').click(function() {
		let input = $(this).siblings('input');
		let qty = parseInt(input.val()) + 1;
		let id = $(this).closest('li').data('id');
		updateQty(id, qty, input);
	});

	function updateQty(id, qty, input) {
		start_load();
		$.ajax({
			url: 'admin/ajax.php?action=update_cart',
			method: 'POST',
			data: { id: id, qty: qty },
			success: function(resp) {
				if (resp == 1) {
					input.val(qty);
					calc();
				}
				end_load();
			}
		});
	}

	$('.rem_item').click(function() {
		let id = $(this).data('id');
		if (confirm("Are you sure to remove this item from cart?")) {
			start_load();
			$.ajax({
				url: 'admin/ajax.php?action=delete_cart',
				method: 'POST',
				data: { id: id },
				success: function(resp) {
					if (resp == 1) {
						alert("Item removed from cart");
						location.reload();
					}
					end_load();
				}
			});
		}
	});

	$('#checkout').click(function() {
		uni_modal('Checkout', 'payment.php');
	});

	// Dummy functions
	function start_load(){ console.log("Loading..."); }
	function end_load(){ console.log("Done."); }
	function uni_modal(title, url){ window.location.href = url; }
	function load_cart(){ console.log("Cart loaded"); }
});
</script>
