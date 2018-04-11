<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <?php $title = 'Page de contact'; ?>

    <!-- Bootstrap core CSS -->
    <link href="public/theme_front/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link href="public/theme_front/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>

    <!-- Custom styles for this template -->
    <link href="css/clean-blog.min.css" rel="stylesheet">

  </head>
<?php ob_start(); ?>
  <!-- Page Header -->
    <header class="masthead" style="background-image: url('public/theme_front/img/contact-bg.jpg')">
      <div class="overlay"></div>
      <div class="container">
        <div class="row">
          <div class="col-lg-8 col-md-10 mx-auto">
            <div class="page-heading">
              <h1>Contatez moi</h1>
              <span class="subheading">Avez vous des question à propos du blog ? J'ai des reponses.</span>
            </div>
          </div>
        </div>
      </div>
    </header>
<?php $header = ob_get_clean(); ?>
<?php ob_start(); ?>
    <div class="row">
        <div class="col-lg-8 col-md-10 mx-auto">
          <p>Voulez-vous entrer en contact? Remplissez le formulaire ci-dessous pour m'envoyer un message et je vous répondrai dans les plus brefs délais!</p>
          <!-- Contact Form - Enter your email address on line 19 of the mail/contact_me.php file to make this form work. -->
          <!-- WARNING: Some web hosts do not allow emails to be sent through forms to common mail hosts like Gmail or Yahoo. It's recommended that you use a private domain email address! -->
          <!-- To use the contact form, your site must be on a live web host with PHP! The form will not work locally! -->
          <form name="sentMessage" id="contactForm" novalidate>
            <div class="control-group">
              <div class="form-group floating-label-form-group controls">
                <label>Nom</label>
                <input type="text" class="form-control" placeholder="Nom" id="name" required data-validation-required-message="S.V.P votre nom.">
                <p class="help-block text-danger"></p>
              </div>
            </div>
            <div class="control-group">
              <div class="form-group floating-label-form-group controls">
                <label>Adresse email</label>
                <input type="email" class="form-control" placeholder="Adresse email" id="email" required data-validation-required-message="S.V.P votre adresse email.">
                <p class="help-block text-danger"></p>
              </div>
            </div>
            <div class="control-group">
              <div class="form-group col-xs-12 floating-label-form-group controls">
                <label>Numero de téléphone</label>
                <input type="tel" class="form-control" placeholder="Numero de téléphone" id="phone" required data-validation-required-message="S.V.P votre numero de téléphone.">
                <p class="help-block text-danger"></p>
              </div>
            </div>
            <div class="control-group">
              <div class="form-group floating-label-form-group controls">
                <label>Message</label>
                <textarea rows="5" class="form-control" placeholder="Message" id="message" required data-validation-required-message="S.V.P votre message."></textarea>
                <p class="help-block text-danger"></p>
              </div>
            </div>
            <br>
            <div id="success"></div>
            <div class="form-group">
              <button type="submit" class="btn btn-primary" id="sendMessageButton">Envoyer</button>
            </div>
          </form>
        </div>
      </div>
  
  <?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>