#Welcome in Essence !

Essence is a framework that will help you create websites.

It is build after the MVC structure and use what is called "Helpers" to help you in your task
as a developer.

##Helper documentation
###Form helper
The Form helper is a powerful tool that can create forms and handle
the submission of the created forms.

To start using this helper :

```
// Controller/DefaultController.php
namespace App\Controller;

use App\Response\View;
use Helper\Form;

class DefaultController
{
    function DefaultAction()
    {
        $form = new Form();

        return new View('default');
    }
}
```

Now you have an instance of the Form helper that is loader under the `$form` variable.
You can start using some of the Form helper methods.

####add( string $fieldName, _TYPE $type, $options = array() )

The `add()` method is what will let you create your form by adding a new field.

**Parameter :**

<ul>
    <li>$fieldName : name of the field</li>
    <li>
        $type : the type of the created field
        <ul>
            <li>TEXT_TYPE</li>
            <li>EMAIL_TYPE</li>
            <li>PASSWORD_TYPE</li>
            <li>SUBMIT_TYPE</li>
        </ul>
    </li>
    <li>
        $options : array of option to further customize your form
        <ul>
            <li>['label'] => The label name of the field. Can be set to false to not use label.</li>
            <li>['class'] => The classes of the input.</li>
            <li>['labelClass'] => The classes of the label.</li>
            <li>['id'] => The id of the input.</li>
            <li>['required'] => set this to true to make the field required.</li>
        </ul>
    </li>
</ul>
<b>Warning !</b> Those options only affects the returned HTML and should not be <br>
considered as a subtitute of server side validation !

Example :
```
$form->add('test', Form::TEXT_TYPE, array('label' => 'Some text :'))
    ->add('submit', Form::SUBMIT_TYPE);
```
This will add an `<input type="text"/>` and a `<button type="submit">`.
```
<label>Some text :</label>
<input type="text" name="test"/>
<button type="submit">submit</button>
```
As you can see the Form helper also created the associated `<label>` for the `'test'` input.

####getForm()

Once you have added all the fields that you want you can use the `getForm()` method.
```
$viewData['form'] = $form->getForm();
```
You now only need to pass `$viewData` to the View you want :
```
return new View('default', $viewData);
```
Now is the time to use your form !

The form is passed to the template like any other variable so you can access it the same manner.
It will be named after the `'key'` it is indexed in the `$viewData` array.\
In our case the form is indexed under the `'form'` key.
```
// Assets/template/default.php
<form method="post" action="#">
    [form]
</form>
```
The `[form]` statement will try to load the 'form.php' partial so let's create it real quick.
```
// Assets/partial/form.php
[test]
[submit]
```
As you might already guest, each field is called by the `$fieldName` used by the `add()` method.
```
// What the user will see
<form method="post" action="#">
    <label for="test" class="">Some text :</label>
    <input id="test" class="" type="text" name="test"/>
    <button type="submit">submit</button>
</form>
```

Good work ! Your form is complete and can be filled by users ! But ...\
Speaking of witch how to retrieve submitted data ?

####handleRequest()

`handleRequest()` is the method used to ask the Form helper to fetch the values of your form.\
Note: `getForm()` must be called after `handleRequest()`.
```
$form->handleRequest();
```
After asking the Form helper to handle the request you can call some new methods.

####isSubmitted()

This method allow you to see if the form is submitted or not.

####getData()

Return an array which contains the submitted values.
```
if ($form->isSubmitted()) {
    $formData = $form->getData();
    
    // ... Do whatever you want with the data ...
}
```
