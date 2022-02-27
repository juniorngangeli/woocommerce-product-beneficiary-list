<div class="ProductBeneficiariesOrderDetails">
    <ul>
        <?php 
            foreach ($product_beneficiaries_list as $beneficiary): 
                $userRow = $beneficiary['first_name'];
                $userRow .= ' ' . $beneficiary['last_name'];
                $userRow .= ' ' . $beneficiary['email'];
        ?>
            <li>
                <?php echo $userRow; ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>