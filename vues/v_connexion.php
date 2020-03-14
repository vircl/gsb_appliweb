<?php
/**
 * Vue Connexion
 *
 * PHP Version 7
 *
 * @category   PPE
 * @package    GSB
 * @subpackage GSB_Vues
 * @author     Virginie CLAUDE <dev@virginie-claude.fr>
 * @copyright  2020 Virginie CLAUDE
 * @license    Virginie CLAUDE
 * @version    GIT: <1>
 * @link       http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */

require './vues/parts/v_titrePage.php'; ?>

<!-- Formulaire d'identification -->
<section id="formIdentitification" class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-12 col-md-8 col-lg-6 shadow-lg p-5">
                <h2 class=""> Identification utilisateur </h2>
                <form role="form"
                      method="post"
                      action="index.php?uc=connexion&action=valideConnexion">
                    <!-- Identifiant -->
                    <div class="form-group">
                        <label for="login" class="sr-only">Identifiant</label>
                        <input type="text"
                               class="form-control"
                               id="login"
                               name="login"
                               maxlength="45"
                               placeholder="Nom d'utilisateur">
                    </div>
                    <!-- Mot de passe -->
                    <div class="form-group">
                        <label for="mdp" class="sr-only">Mot de passe</label>
                        <input type="password"
                               class="form-control"
                               id="mdp"
                               name="mdp"
                               maxlength="45"
                               placeholder="Mot de passe">
                    </div>
                    <!-- Submit -->
                    <div class="form-group text-center">
                        <button type="submit"
                                class="btn btn-primary">
                            Se connecter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

