<?php
$user = getUserDetails($_SESSION['user_id']);
$addresses = getUserAddresses($_SESSION['user_id']);
$message = '';
$error = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        if (updateUserDetails($_SESSION['user_id'], $_POST['username'], $_POST['email'])) {
            $_SESSION['username'] = $_POST['username'];
            $message = 'Profile updated successfully!';
        } else {
            $error = 'Failed to update profile.';
        }
    }
    
    if (isset($_POST['change_password'])) {
        if (changePassword($_SESSION['user_id'], $_POST['old_password'], $_POST['new_password'])) {
            $message = 'Password changed successfully!';
        } else {
            $error = 'Current password is incorrect.';
        }
    }
    
    if (isset($_POST['add_address'])) {
        if (addAddress($_SESSION['user_id'], $_POST['address_line1'], $_POST['address_line2'], $_POST['city'], $_POST['postcode'], $_POST['country'])) {
            $message = 'Address added successfully!';
        } else {
            $error = 'Failed to add address.';
        }
    }
    
    if (isset($_POST['delete_address'])) {
        if (deleteAddress($_POST['address_id'], $_SESSION['user_id'])) {
            $message = 'Address deleted successfully!';
        } else {
            $error = 'Failed to delete address.';
        }
    }
}
?>

<!-- Messages -->
<?php if ($message): ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        <?php echo $message; ?>
    </div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-error">
        <i class="fas fa-exclamation-circle"></i>
        <?php echo $error; ?>
    </div>
<?php endif; ?>

<!-- Profile Information -->
<div class="admin-form-container">
    <div class="admin-badge">
        <i class="fas fa-user-circle"></i>
        <span class="admin-badge-text">Profile Information</span>
    </div>
    
    <form method="POST" class="admin-form">
        <div class="form-group">
            <label for="username">
                <i class="fas fa-user"></i>
                Username
            </label>
            <input type="text" id="username" name="username" class="form-control" 
                   value="<?php echo htmlspecialchars($user['username']); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="email">
                <i class="fas fa-envelope"></i>
                Email Address
            </label>
            <input type="email" id="email" name="email" class="form-control" 
                   value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>
        
        <button type="submit" name="update_profile" class="btn-submit">
            <i class="fas fa-save"></i>
            Update Profile
        </button>
    </form>
</div>

<!-- Change Password -->
<div class="admin-form-container">
    <div class="admin-badge">
        <i class="fas fa-lock"></i>
        <span class="admin-badge-text">Change Password</span>
    </div>
    
    <form method="POST" class="admin-form">
        <div class="form-group">
            <label for="old_password">
                <i class="fas fa-key"></i>
                Current Password
            </label>
            <input type="password" id="old_password" name="old_password" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="new_password">
                <i class="fas fa-lock"></i>
                New Password
            </label>
            <input type="password" id="new_password" name="new_password" class="form-control" required>
        </div>
        
        <button type="submit" name="change_password" class="btn-submit">
            <i class="fas fa-sync-alt"></i>
            Change Password
        </button>
    </form>
</div>

<!-- Addresses -->
<div class="admin-form-container" id="addresses">
    <div class="admin-badge">
        <i class="fas fa-map-marker-alt"></i>
        <span class="admin-badge-text">Saved Addresses</span>
    </div>
    
    <!-- Add Address Button -->
    <div style="margin-bottom: 2rem;">
        <button onclick="toggleAddressForm()" class="btn-secondary">
            <i class="fas fa-plus-circle"></i>
            Add New Address
        </button>
    </div>
    
    <!-- Add Address Form (hidden by default) -->
    <div id="addressForm" style="display: none; margin-bottom: 2rem; padding: 1.5rem; background: #f9fafb; border-radius: 0.75rem;">
        <h3 style="font-size: 1.125rem; font-weight: 600; color: #111827; margin-bottom: 1.5rem;">
            <i class="fas fa-plus-circle"></i>
            Add New Address
        </h3>
        
        <form method="POST">
            <div class="form-group">
                <label for="address_line1">Address Line 1</label>
                <input type="text" id="address_line1" name="address_line1" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="address_line2">Address Line 2 (Optional)</label>
                <input type="text" id="address_line2" name="address_line2" class="form-control">
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" id="city" name="city" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="postcode">Postcode</label>
                    <input type="text" id="postcode" name="postcode" class="form-control" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="country">Country</label>
                <input type="text" id="country" name="country" class="form-control" required>
            </div>
            
            <div style="display: flex; gap: 1rem;">
                <button type="submit" name="add_address" class="btn-submit">
                    <i class="fas fa-save"></i>
                    Save Address
                </button>
                <button type="button" onclick="toggleAddressForm()" class="btn-secondary">
                    <i class="fas fa-times"></i>
                    Cancel
                </button>
            </div>
        </form>
    </div>
    
    <!-- Address List -->
    <?php if ($addresses->num_rows > 0): ?>
        <div class="address-grid">
            <?php while($address = $addresses->fetch_assoc()): ?>
            <div class="address-card">
                <div class="address-header">
                    <span class="address-badge">
                        <i class="fas fa-check-circle"></i>
                        Saved Address
                    </span>
                    <div class="address-actions">
                        <form method="POST" onsubmit="return confirm('Are you sure you want to delete this address?');">
                            <input type="hidden" name="address_id" value="<?php echo $address['address_id']; ?>">
                            <button type="submit" name="delete_address" class="btn-delete btn-sm">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
                <div class="address-content">
                    <p><strong><?php echo htmlspecialchars($address['address_line1']); ?></strong></p>
                    <?php if (!empty($address['address_line2'])): ?>
                        <p><?php echo htmlspecialchars($address['address_line2']); ?></p>
                    <?php endif; ?>
                    <p><?php echo htmlspecialchars($address['city'] . ', ' . $address['postcode']); ?></p>
                    <p><?php echo htmlspecialchars($address['country']); ?></p>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div style="text-align: center; padding: 3rem; background: #f9fafb; border-radius: 0.75rem;">
            <i class="fas fa-map-marker-alt" style="font-size: 3rem; color: #d1d5db; margin-bottom: 1rem;"></i>
            <p style="color: #6b7280;">No addresses saved yet.</p>
            <p style="color: #9ca3af; font-size: 0.875rem; margin-top: 0.5rem;">Add an address to make checkout faster!</p>
        </div>
    <?php endif; ?>
</div>

<script>
function toggleAddressForm() {
    var form = document.getElementById('addressForm');
    if (form.style.display === 'none' || form.style.display === '') {
        form.style.display = 'block';
    } else {
        form.style.display = 'none';
    }
}
</script>