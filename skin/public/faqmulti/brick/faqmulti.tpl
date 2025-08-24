{if isset($advmulti) && $advmulti != null}
    <section id="advmulti" class="clearfix">
        <div class="container">
            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true" itemprop="mainEntity" itemscope itemtype="http://schema.org/ItemList">
                <meta itemprop="itemListOrder" content="Unordered" />
                <meta itemprop="numberOfItems" content="{$advmulti|count}" />
                {foreach $faqmulti as $QA}
                    <div class="panel panel-default" itemprop="itemListElement" itemscope itemtype="http://schema.org/Question">
                        <meta itemprop="answerCount" content="1">
                        <div class="panel-heading" role="tab" id="heading{$QA.id_faqmulti}">
                            <h2 class="panel-title {*open*} collapsed" data-toggle="collapse" data-parent="#accordion" data-target="#qa_{$QA.id_faqmulti}" aria-expanded="true" aria-controls="collapse{$QA@index +1}">
                                <span itemprop="name">{$QA.title_faqmulti}</span>
                                <span class="icon">
                                    <span class="show-more"><i class="ico ico-add"></i></span>
                                    <span class="show-less"><i class="ico ico-remove"></i></span>
                                </span>
                            </h2>
                        </div>
                        <div id="qa_{$QA.id_faqmulti}" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading{$QA.id_faqmulti}">
                            <div class="panel-body" itemprop="acceptedAnswer" itemscope itemtype="http://schema.org/Answer">
                                <span itemprop="answerExplanation">{$QA.desc_faqmulti}</span>
                            </div>
                        </div>
                    </div>
                {/foreach}
            </div>
        </div>
    </section>
{/if}