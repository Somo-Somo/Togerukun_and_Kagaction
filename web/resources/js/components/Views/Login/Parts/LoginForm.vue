<template>
    <div>
        <v-text-field
            v-model="formValue.name"
            v-if="!isLoginForm"
            :error-messages="errorMessagesName()"
            autofocus
            dense
            height="48px"
            outlined
            placeholder="ユーザ名"
        ></v-text-field>

        <v-text-field
            v-model="formValue.email"
            :error-messages="errorMessagesEmail()"
            autofocus
            dense
            height="48px"
            outlined
            placeholder="メールアドレス"
        ></v-text-field>

        <v-text-field
            v-model="formValue.password"
            :append-icon="passwordShow ? 'mdi-eye' : 'mdi-eye-off'"
            :type="passwordShow ? 'text' : 'password'"
            :error-messages="errorMessagesPassword()"
            dense
            height="48px"
            name="input-password"
            outlined
            placeholder="パスワード"
            @click:append="passwordShow = !passwordShow"
        ></v-text-field>
        <div
            v-if="!isLoginForm"
            style="position: relative; top: 8px"
            :style="!errorMessages ? 'top: -16px;' : 'top: -8px;'"
        >
            <ul>
                <li>半角英数字・記号8文字以上</li>
                <li>半角英字・数字それぞれ一文字以上含む</li>
            </ul>
        </div>
    </div>
</template>

<script>
import SuccessAlert from "../../../Common/Parts/Atom/SuccessAlert.vue";
import LoginCardTitle from "../Parts/LoginCardTitle.vue";

export default {
    components: {
        SuccessAlert,
        LoginCardTitle,
    },
    data: () => ({
        passwordShow: false,
    }),
    props: {
        isLoginForm: {
            type: Boolean,
        },
        loginAndRegisterOfErrorMessages: {
            type: Object,
        },
        formValue: {
            type: Object,
        },
    },
    computed: {
        errorMessagesName() {
            return () => {
                return this.loginAndRegisterOfErrorMessages.register
                    ? this.getNameErrorMessage(
                          this.loginAndRegisterOfErrorMessages.register
                      )
                    : null;
            };
        },
        errorMessagesEmail() {
            return () => {
                const errorMessages = this.errorMessages();
                return errorMessages
                    ? this.getEmailErrorMassage(errorMessages)
                    : null;
            };
        },
        errorMessagesPassword() {
            return () => {
                const errorMessages = this.errorMessages();
                return errorMessages
                    ? this.getPasswordErrorMassage(errorMessages)
                    : null;
            };
        },
    },
    methods: {
        errorMessages() {
            return this.isLoginForm
                ? this.loginAndRegisterOfErrorMessages.login
                : this.loginAndRegisterOfErrorMessages.register;
        },
        getNameErrorMessage(errorMessages) {
            return errorMessages["name"] !== undefined
                ? errorMessages["name"][0]
                : null;
        },
        getEmailErrorMassage(errorMessages) {
            return errorMessages["email"] !== undefined
                ? errorMessages["email"][0]
                : null;
        },
        getPasswordErrorMassage(errorMessages) {
            return errorMessages["password"] !== undefined
                ? errorMessages["password"][0]
                : null;
        },
    },
};
</script>
