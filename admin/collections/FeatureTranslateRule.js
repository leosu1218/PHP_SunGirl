
define(['./collections/ORM'], function (ORM) {

    return ORM.collection('/api/imediaevent/translate-rule', {
        list: '/api/imediaevent/translate-rule/list',
    });
});