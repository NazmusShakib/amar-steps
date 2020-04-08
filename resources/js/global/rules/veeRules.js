import Vue from 'vue';
import VeeValidate from 'vee-validate';
import VeeValidateLaravel from '@pmochine/vee-validate-laravel';

Vue.prototype.$setErrorsFromResponse = function(errorResponse) {
    // only allow this function to be run if the validator exists
    if(!this.hasOwnProperty('$validator')) {
        return;
    }

    // clear errors
    this.$validator.errors.clear();

    // check if errors exist
    if(!errorResponse.hasOwnProperty('errors')) {
        return;
    }

    let errorFields = Object.keys(errorResponse.errors);

    // insert laravel errors
    errorFields.map(field => {
        let errorString = errorResponse.errors[field].join(', ');
        this.$validator.errors.add({ field: field, msg: errorString });
    });
};



const dictionary = {
    custom: {
        staff_rate: {
            required: 'The staff rate field is required',
            decimal: 'The staff rate field must be numeric and may contain 2 decimal points',
            min_value: 'The staff rate field must be 0 or more',
            max_value: ' The staff rate field must be 99 or less '
        },
        acc_number: {
            required: 'The acc number field is required',
            min: 'The acc number field must be at least 11 characters',
            max: 'The acc number field may not be greater than 11 characters',
            digits: 'The acc number field must be numeric and contains exactly 11 digits'
        },
        unit_id: {
            required: 'The unit field is required.'
        }
    }
};
VeeValidate.Validator.localize('en', dictionary);
Vue.use(VeeValidate, {
    events: 'input|change|blur',
});
Vue.use(VeeValidateLaravel, {
    events: 'input|change|blur',
});

export default {

}
