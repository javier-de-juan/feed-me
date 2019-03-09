<div class="wrap">
    <h1>{% title %}</h1>
    <form method="post" action="options.php" novalidate="novalidate">

        {% wordpress_registered_hidden_fields %}

        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row"><label for="trello-api-key">{'Trello API Key'}</label></th>
                    <td><input name="{{PLUGIN}}-trello-api-key" id="trello-api-key" value="{% trello_api_key %}" class="regular-text" type="text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="trello-api-token">{'Trello API Token'}</label></th>
                    <td><input name="{{PLUGIN}}-trello-api-token" id="trello-api-token" value="{% trello_api_token %}" class="regular-text" type="text"></td>
                </tr>

                <tr>
                    <th scope="row"><label for="trello-board">{'Target board'}</label></th>
                    <td>
                        <select name="{{PLUGIN}}-trello-board" id="trello-board">
                            {% trello_boards | settings %}
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>

        <p>{% trello_help %}</p>

        <p class="submit">
            <input name="submit" id="submit" class="button button-primary" value="{'Save changes'}" type="submit">
        </p>
    </form>

</div>