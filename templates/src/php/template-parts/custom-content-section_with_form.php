<?php

$type = $item['form_type'];
$title = $item['title'];

?>

<?php
 // formularz z liczba pokoi
if ($type == 'with_rooms') {
    ?>

<div class="row">
    <div class="col-12">
        <div class="form-wrapper">
            <form action="">
                <div class="form-content">
                    <h2 class="title"><?php echo $title; ?></h2>
                    <input type="text" name="first-name" placeholder="<?php _e('Imię', 'echo'); ?> *">
                    <input type="text" name="last-name" placeholder="<?php _e('Nazwisko', 'echo'); ?> *">
                    <div class="select rooms-select d-flex d-md-none">
                        <div class="select-title mr-auto">
                            <p>Preferowana liczba pokoi *</p>
                        </div>
                        <div class="select-button">
                            <p class="select-current">1</p>
                            <div class="select-dropdown">
                                <label>
                                    <input name="rooms" value="1" checked type="radio">1
                                </label>
                                <label>
                                    <input name="rooms" value="2" type="radio">2
                                </label>
                                <label>
                                    <input name="rooms" value="3" type="radio">3
                                </label>
                                <label>
                                    <input name="rooms" value="4" type="radio">4
                                </label>
                                <label>
                                    <input name="rooms" value="5" type="radio">5
                                </label>
                            </div>
                        </div>
                    </div>
                    <input type="text" name="email" placeholder="<?php _e('E-mail', 'echo'); ?> *">
                    <div class="invalid-email-at"><?php _e('Brakuje @', 'echo'); ?></div>
                    <input type="text" name="phone" placeholder="<?php _e('Telefon', 'echo'); ?> *">
                    <div class="select rooms-select d-none d-md-flex">
                        <div class="select-title mr-auto">
                            <p>Preferowana liczba pokoi *</p>
                        </div>
                        <div class="select-button">
                            <p class="select-current">1</p>
                            <div class="select-dropdown">
                                <label>
                                    <input name="rooms" value="1" checked type="radio">1
                                </label>
                                <label>
                                    <input name="rooms" value="2" type="radio">2
                                </label>
                                <label>
                                    <input name="rooms" value="3" type="radio">3
                                </label>
                                <label>
                                    <input name="rooms" value="4" type="radio">4
                                </label>
                                <label>
                                    <input name="rooms" value="5" type="radio">5
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="agreements">

                        <label class="status-info">
                            <?php _e('Przed wyrażeniem zgód zapoznaj się z informacjami o przetwarzaniu danych.', 'echo'); ?>
                        </label>

                        <label class="status-info">
                            <?php printf(__('<a href="%s" target="_blank">Administratorem Państwa danych osobowych jest Echo Investment <i class="icon-question-circle"></i></a>', 'echo'), get_permalink(get_page_by_path('skrocona-polityka-prywatnosci'))); ?>
                        </label>

                        <label class="status">
                            <input type="checkbox" name="agree_all" class="js--agree-all">
                            <div class="consent"><?php _e('Wyrażam wszystkie poniższe zgody.', 'echo'); ?></div>
                        </label>

                        <label class="status">
                            <input type="checkbox" name="agree">
                            <div class="consent">
                                <div>
                                    <?php _e('Wyrażam zgodę na przetwarzanie moich danych osobowych', 'echo'); ?>*
                                    <i class="icon-info" aria-hidden="true" data-toggle="tooltip" data-html="true" data-placement="top" id="tooltip" title="<?php _e('Wyrażam zgodę na przetwarzanie moich danych osobowych przez Echo Investment S.A. dla celów marketingowych, tj. w celu prezentacji ofert Echo Investment S.A. oraz spółek inwestycyjnych z grupy Echo**.<br><br>** „Spółki inwestycyjne z grupy Echo” oznaczają podmioty powiązane kapitałowo z Echo Investment S.A. powołane w celu realizacji inwestycji i sprzedaży nieruchomości klientom indywidualnym. Pełna oraz aktualna lista takich podmiotów znajduje się na stronie internetowej www.echo.com.pl/rodo.', 'echo'); ?>"></i>
                                </div>
                            </div>
                        </label>

                        <label class="status">
                            <input type="checkbox" name="agree2">
                            <div class="consent">
                                <div>
                                    <?php _e('Wyrażam zgodę na przesyłanie mi informacji marketingowych i ofert handlowych za pomocą środków komunikacji elektronicznej ', 'echo'); ?>*
                                    <i class="icon-info" aria-hidden="true" data-toggle="tooltip" data-html="true" data-placement="top" id="tooltip" title="<?php _e('Wyrażam zgodę na przesyłanie mi informacji marketingowych i ofert handlowych za pomocą środków komunikacji elektronicznej zgodnie z art. 10 ustawy z dnia 18 lipca 2002 o świadczeniu usług drogą elektroniczną, w tym w szczególności na podane przeze mnie adresy email, przez Echo Investment S.A., dotyczących ofert Echo Investment S.A. oraz spółek inwestycyjnych z grupy Echo**.<br><br>** „Spółki inwestycyjne z grupy Echo” oznaczają podmioty powiązane kapitałowo z Echo Investment S.A. powołane w celu realizacji inwestycji i sprzedaży nieruchomości klientom indywidualnym. Pełna oraz aktualna lista takich podmiotów znajduje się na stronie internetowej www.echo.com.pl/rodo', 'echo'); ?>"></i>
                                </div>
                            </div>
                        </label>

                        <label class="status">
                            <input type="checkbox" name="agree3">
                            <div class="consent">
                                <div>
                                    <?php _e('Wyrażam zgodę na prowadzenie marketingu bezpośredniego (przy użyciu urządzeń telekomunikacyjnych, w szczególności telefonu)', 'echo'); ?>*
                                    <i class="icon-info" aria-hidden="true" data-toggle="tooltip" data-placement="top" data-html="true" id="tooltip" title="<?php _e('Wyrażam zgodę na prowadzenie marketingu bezpośredniego, w szczególności  przedstawianie mi informacji marketingowych i ofert handlowych przy użyciu telekomunikacyjnych urządzeń końcowych i automatycznych systemów wywołujących zgodnie z art. 172 ust. 1 ustawy z dnia 16 lipca 2004 r. Prawo telekomunikacyjne, poprzez m.in. komunikację za pomocą telefonu i wiadomości SMS/MMS skierowaną na podane przeze mnie numery telefonów, przez Echo Investment S.A., dotyczących ofert Echo Investment S.A. oraz spółek inwestycyjnych z grupy Echo**.<br><br>** „Spółki inwestycyjne z grupy Echo” oznaczają podmioty powiązane kapitałowo z Echo Investment S.A. powołane w celu realizacji inwestycji i sprzedaży nieruchomości klientom indywidualnym. Pełna oraz aktualna lista takich podmiotów znajduje się na stronie internetowej www.echo.com.pl/rodo', 'echo'); ?>"></i>
                                </div>
                            </div>
                        </label>

                        <div class="agree-invalid text-red"><?php _e('Drogi Kliencie, wyraziłeś tylko niektóre z dostępnych zgód. Wyrażenie wszystkich zgód jest dobrowolne. Zwracamy jednak uwagę, że w celu wysłanie formularza należy zaakceptować wszystkie 3 zgody. W przypadku braku jakiejkolwiek zgody prosimy o kontakt telefoniczny lub osobisty w biurze sprzedaży. Dziękujemy!', 'echo'); ?></div>
                    </div>
                    <div class="submit">
                        <div class="button-verification">
                            <p class="required-field">*<?php _e('Pola wymagane', 'echo'); ?></p>
                            <div class="submit-response"></div>
                            <div class="invalid-verification text-red"><?php _e('Formularz jest niepełny.', 'echo'); ?></div>
                            <button class="button submit-button btn" data-sitekey="<?php echo CAPTCHA_KEY; ?>" data-callback="onSubmit" type="submit">
                                <?php _e('Wyślij', 'echo'); ?>
                            </button>
                        </div>
                        <input type="hidden" name="form" value="contact">
                        <input type="hidden" name="subject" value="Wiadomość ze strony <?php echo bloginfo('url') ?>">
                        <input type="hidden" id="ajax-send-form" name="ajax-send-form" value="<?php echo wp_create_nonce('send_form'); ?>">
                    </div>
                </div>
                <div class="form-thanks">
                    <h2><i><?php _e('Dziękujemy za zainteresowanie. Wkrótce skontaktujemy się z Państwem.', 'echo'); ?></i></h2>
                </div>
            </form>
        </div>
    </div>
</div>

<?php 
    // standardowy formularz
} else {
    ?>

<div class="row">
    <div class="col-12">
        <div class="form-wrapper">
            <form action="">
                <div class="form-content text--js">
                    <h2 class="title"><?php echo $title; ?></h2>
                    <div class="row">
                        <div class="col-12">
                            <input type="text" name="first-name" placeholder="<?php _e('Imię', 'echo'); ?> *">
                            <input type="text" name="last-name" placeholder="<?php _e('Nazwisko', 'echo'); ?> *">
                            <input type="text" name="email" placeholder="<?php _e('E-mail', 'echo'); ?> *">
                            <input type="text" name="phone" placeholder="<?php _e('Telefon', 'echo'); ?> *">
                        </div>
                        <div class="col-12">
                            <div class="textarea">
                                <p><?php _e('Treść wiadomości', 'echo'); ?> *</p>
                                <textarea name="message" cols="30" rows="7"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="agreements">

                        <label class="status-info">
                            <?php _e('Przed wyrażeniem zgód zapoznaj się z informacjami o przetwarzaniu danych.', 'echo'); ?>
                        </label>

                        <label class="status-info">
                            <?php printf(__('<a href="%s" target="_blank">Administratorem Państwa danych osobowych jest Echo Investment <i class="icon-question-circle"></i></a>', 'echo'), get_permalink(get_page_by_path('skrocona-polityka-prywatnosci'))); ?>
                        </label>

                        <label class="status">
                            <input type="checkbox" name="agree_all" class="js--agree-all">
                            <div class="consent"><?php _e('Wyrażam wszystkie poniższe zgody.', 'echo'); ?></div>
                        </label>

                        <label class="status">
                            <input type="checkbox" name="agree">
                            <div class="consent">
                                <div>
                                    <?php _e('Wyrażam zgodę na przetwarzanie moich danych osobowych', 'echo'); ?>*
                                    <i class="icon-info" aria-hidden="true" data-toggle="tooltip" data-html="true" data-placement="top" id="tooltip" title="<?php _e('Wyrażam zgodę na przetwarzanie moich danych osobowych przez Echo Investment S.A. dla celów marketingowych, tj. w celu prezentacji ofert Echo Investment S.A. oraz spółek inwestycyjnych z grupy Echo**.<br><br>** „Spółki inwestycyjne z grupy Echo” oznaczają podmioty powiązane kapitałowo z Echo Investment S.A. powołane w celu realizacji inwestycji i sprzedaży nieruchomości klientom indywidualnym. Pełna oraz aktualna lista takich podmiotów znajduje się na stronie internetowej www.echo.com.pl/rodo.', 'echo'); ?>"></i>
                                </div>
                            </div>
                        </label>

                        <label class="status">
                            <input type="checkbox" name="agree2">
                            <div class="consent">
                                <div>
                                    <?php _e('Wyrażam zgodę na przesyłanie mi informacji marketingowych i ofert handlowych za pomocą środków komunikacji elektronicznej ', 'echo'); ?>*
                                    <i class="icon-info" aria-hidden="true" data-toggle="tooltip" data-html="true" data-placement="top" id="tooltip" title="<?php _e('Wyrażam zgodę na przesyłanie mi informacji marketingowych i ofert handlowych za pomocą środków komunikacji elektronicznej zgodnie z art. 10 ustawy z dnia 18 lipca 2002 o świadczeniu usług drogą elektroniczną, w tym w szczególności na podane przeze mnie adresy email, przez Echo Investment S.A., dotyczących ofert Echo Investment S.A. oraz spółek inwestycyjnych z grupy Echo**.<br><br>** „Spółki inwestycyjne z grupy Echo” oznaczają podmioty powiązane kapitałowo z Echo Investment S.A. powołane w celu realizacji inwestycji i sprzedaży nieruchomości klientom indywidualnym. Pełna oraz aktualna lista takich podmiotów znajduje się na stronie internetowej www.echo.com.pl/rodo', 'echo'); ?>"></i>
                                </div>
                            </div>
                        </label>

                        <label class="status">
                            <input type="checkbox" name="agree3">
                            <div class="consent">
                                <div>
                                    <?php _e('Wyrażam zgodę na prowadzenie marketingu bezpośredniego (przy użyciu urządzeń telekomunikacyjnych, w szczególności telefonu)', 'echo'); ?>*
                                    <i class="icon-info" aria-hidden="true" data-toggle="tooltip" data-placement="top" data-html="true" id="tooltip" title="<?php _e('Wyrażam zgodę na prowadzenie marketingu bezpośredniego, w szczególności  przedstawianie mi informacji marketingowych i ofert handlowych przy użyciu telekomunikacyjnych urządzeń końcowych i automatycznych systemów wywołujących zgodnie z art. 172 ust. 1 ustawy z dnia 16 lipca 2004 r. Prawo telekomunikacyjne, poprzez m.in. komunikację za pomocą telefonu i wiadomości SMS/MMS skierowaną na podane przeze mnie numery telefonów, przez Echo Investment S.A., dotyczących ofert Echo Investment S.A. oraz spółek inwestycyjnych z grupy Echo**.<br><br>** „Spółki inwestycyjne z grupy Echo” oznaczają podmioty powiązane kapitałowo z Echo Investment S.A. powołane w celu realizacji inwestycji i sprzedaży nieruchomości klientom indywidualnym. Pełna oraz aktualna lista takich podmiotów znajduje się na stronie internetowej www.echo.com.pl/rodo', 'echo'); ?>"></i>
                                </div>
                            </div>
                        </label>

                        <div class="agree-invalid text-red"><?php _e('Drogi Kliencie, wyraziłeś tylko niektóre z dostępnych zgód. Wyrażenie wszystkich zgód jest dobrowolne. Zwracamy jednak uwagę, że w celu wysłanie formularza należy zaakceptować wszystkie 3 zgody. W przypadku braku jakiejkolwiek zgody prosimy o kontakt telefoniczny lub osobisty w biurze sprzedaży. Dziękujemy!', 'echo'); ?></div>

                    </div>
                    <div class="submit">
                        <div class="button-verification">
                            <p class="required-field">*<?php _e('Pola wymagane', 'echo'); ?></p>
                            <div class="submit-response"></div>
                            <div class="invalid-verification text-red"><?php _e('Formularz jest niepełny.', 'echo'); ?></div>
                            <button class="button submit-button btn" data-sitekey="<?php echo CAPTCHA_KEY; ?>" data-callback="onSubmit" type="submit">
                                <?php _e('Wyślij', 'echo'); ?>
                            </button>
                        </div>
                        <input type="hidden" name="form" value="contact">
                        <input type="hidden" name="subject" value="Wiadomość ze strony <?php echo bloginfo('url') ?>">
                        <input type="hidden" id="ajax-send-form" name="ajax-send-form" value="<?php echo wp_create_nonce('send_form'); ?>">
                    </div>
                </div>
                <div class="form-thanks">
                    <h2><i><?php _e('Dziękujemy za zainteresowanie. Wkrótce skontaktujemy się z Państwem.', 'echo'); ?></i></h2>
                </div>
            </form>
        </div>
    </div>
</div>

<?php 
}
