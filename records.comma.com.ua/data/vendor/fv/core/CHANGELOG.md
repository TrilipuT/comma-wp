## 1.5

#### General:

- Subclasses can have their own fields. Usage:
    
    ```yml
    entities:
      Basic:
        subclass_key: subclass
        fields:
          commonField: { type: string }
            
      Specific:
        extends: Basic
        fields:
          specificField: { type: string }
    ```

## 1.4

#### General:
- Bundles with namespaces, with capability of own views
- Apps needs own class `BackendApp`, `FrontendApp`. It's need to load application-relative bundles, such as `AdminBundle`
- Now `fvController` use `views/controllers/controller-name` template (not `views/controllers/controller-name/action-name`)
- New class `fvMultiController`, wich implements old `views/controllers/controller-name/action-name` logic.

#### Features:
- Url Generator `{{ path("controller-name") }}` is now same as `{{ path("controller-name:index") }}`
- `fvImageQuery::fit()` method — aspect-based resize to exact sizes with color fills paddings
- `fvDictionary::hasTranslate()` method
- Default field translations in forms. if no translation for `Form_Name.fields.field_name` and have translation for `defaults.fields.field_name` the last one will be used.
- Default validators translations in forms. if no translation for `Form_Name.validators.validator` and have translation for `defaults.validators.validator`, the last one will be used.
- Parameters for ParamConverter. Syntax: `@converter $id $user Entity(User, url)`
- New ParamConverter_Entity class. Default's logic for converting params to Entity. good! ))
- Assets MiddleWare

