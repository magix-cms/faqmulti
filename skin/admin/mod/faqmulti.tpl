<div class="row">
    <form id="edit_faqmulti" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}{if isset($smarty.get.edit)}&amp;action=edit&amp;edit={$smarty.get.edit}{/if}&amp;plugin={$smarty.get.plugin}&amp;mod_action={if !$edit}add{else}edit{/if}" method="post" class="validate_form{if !$edit} add_form collapse in{else} edit_form{/if} col-ph-12 col-lg-12">
        {include file="language/brick/dropdown-lang.tpl"}
        <div class="row">
            <div class="col-ph-12">
                <div class="tab-content">
                    {foreach $langs as $id => $iso}
                        <div role="tabpanel" class="tab-pane{if $iso@first} active{/if}" id="lang-{$id}">
                            <fieldset>
                                <legend>Texte</legend>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">
                                        <div class="form-group">
                                            <label for="title_faqmulti_{$id}">{#title_faqmulti#|ucfirst} :</label>
                                            <input type="text" class="form-control" id="title_faqmulti_{$id}" name="content[{$id}][title_faqmulti]" value="{$faqmulti.content[{$id}].title_faqmulti}" />
                                        </div>
                                        <div class="form-group">
                                            <label for="desc_faqmulti_{$id}">{#desc_faqmulti#|ucfirst} :</label>
                                            <textarea id="desc_faqmulti_{$id}" name="content[{$id}][desc_faqmulti]" class="form-control mceEditor">{call name=cleantextarea field=$faqmulti.content[{$id}].desc_faqmulti}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                                        <div class="form-group">
                                            <label for="published_faqmulti_{$id}">Statut</label>
                                            <input id="published_faqmulti_{$id}" data-toggle="toggle" type="checkbox" name="content[{$id}][published_faqmulti]" data-on="Publiée" data-off="Brouillon" data-onstyle="success" data-offstyle="danger"{if (!isset($faqmulti) && $iso@first) || $faqmulti.content[{$id}].published_faqmulti} checked{/if}>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    {/foreach}
                </div>
            </div>
        </div>
        <fieldset>
            <legend>Enregistrer</legend>
            {if $edit}
                <input type="hidden" name="content[id]" value="{$faqmulti.id_faqmulti}" />
            {/if}
            <button class="btn btn-main-theme" type="submit">{#save#|ucfirst}</button>
        </fieldset>
    </form>
</div>