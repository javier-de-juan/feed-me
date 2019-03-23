<div id="{{PLUGIN}}">
    <a href="#" class="launcher">{% title %}</a>

    <div class="wrapper rejillas">
        <div>
            <h2>{% title %}
                <button aria-label="{'close'}" class="close">&times;</button>
            </h2>

            <form action="{% action_url %}" data-ajax-url="{% ajax_url %}" method="post" enctype="multipart/form-data">
                <fieldset>
                    <label>{'Feedback type'}*:
                        <select name="{{PLUGIN}}[type]" size="4" required="required" >
                            {% columns | options %}
                        </select>
                    </label>

                    <label>{'Title'}*:
                        <input name="{{PLUGIN}}[title]" required="required" placeholder="{'Your feedback at a glance'}" type="text">
                    </label>

                    <label>{'Description'}*:
                        <textarea rows="14" id="description" name="{{PLUGIN}}[description]" placeholder="{% description_placeholder %}" required="required"></textarea>
                    </label>

                    <div id="{{PLUGIN}}-attachment" class="not-important">
                        <label> {'Attach file'}: </label>
                        <button class="attachment" id="attachment_button" type="button">{'Search'}</button>
                        <input id="attachment" name="{{PLUGIN}}" class="hidden" type="file">
                    </div>

                    <div class="feed-button">
                        <button type="submit">{'Send'}</button>
                    </div>
                </fieldset>

                <input type="hidden" name="{{PLUGIN}}[url]" value="{% url %}"/>
                <input type="hidden" name="action" value="{% ajax_action %}"/>
            </form>
            <div class="feedme-notify success"><p>Esto es un mensaje de éxito</p></div>
        </div>
    </div>

</div>