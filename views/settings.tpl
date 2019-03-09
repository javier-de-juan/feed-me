<div class="wrap">
    <h1>{% title %}</h1>
    <form method="post" action="options.php" novalidate="novalidate">
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row"><label for="key">{'Trello API Key'}</label></th>
                    <td><input name="{{PLUGIN}}-key" id="key" value="{% key %}" class="regular-text" type="text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="token">{'Trello API Token'}</label></th>
                    <td><input name="{{PLUGIN}}-token" id="token" value="{% token %}" class="regular-text" type="text"></td>
                </tr>
            </tbody>
        </table>

        <p>{% trello_help %}</p>

        {% wordpess_fields %}

        <p class="submit">
            <input name="submit" id="submit" class="button button-primary" value="{'Save changes'}" type="submit">
        </p>
    </form>

</div>