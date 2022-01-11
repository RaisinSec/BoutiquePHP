<?php $this->title = "Paiement"; ?>
<!-- Formulaire de remplissage -->

<?= "Prix à payer : '$price'" ?>

<div class="container">
    <div class="text-center">
        <h1>Veuillez saisir les informations</h1>
    </div>
    <div class="containerLogin">
        <form action="index.php?page=profil" method='post'>
            <div class="row">
                <div class="col">
                    <label for="inputFirstName">Prénom</label>
                    <input type="text" class="form-control" name="checkout_form_firstname" placeholder="Jean" required>
                </div>
                <div class="col">
                    <label for="inputSurname">Nom</label>
                    <input type="text" class="form-control" name="checkout_form_surname" placeholder="De la Fontaine" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="inputAdd1">Adresse</label>
                <input type="text" class="form-control" name="checkout_form_add1" placeholder="25 rue Jean Moulin" required>
            </div>
            <div class="row">
                <div class="col">
                    <label for="inputCity">Ville</label>
                    <input type="text" class="form-control" name="checkout_form_city" placeholder="Ex : Villeurbanne" required>
                </div>
                <div class="col">
                    <label for="inputPostCode">Code Postal</label>
                    <input type="text" class="form-control" name="checkout_form_postcode" placeholder="Ex : 69100" required>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label for="inputPhone">Numéro de téléphone</label>
                    <input type="text" class="form-control" name="checkout_form_phone" placeholder="Ex : 06 12 34 56 78" required>
                </div>
                <div class="col">
                    <label for="inputEMail">Adresse e-mail</label>
                    <input type="text" class="form-control" name="checkout_form_email" placeholder="nom@exemple.com" required>
                </div>
            </div>
            <br/>
            <div class="text-center">
                <h5>Choisissez votre mode de paiement</h5>
            </div>
            <select class="form-select">
                <option selected value="1">Paypal</option>
                <option value="2">Chèque</option>
                <option value="3">Carte Bancaire</option>
            </select>
            <div class="buttonHolder">
                <button class="btn1" type="submit" name='checkout-request'>Paiement</button>
            </div>
            
        </form>
    </div>
</div>