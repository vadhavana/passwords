<template>
    <div class="background" id="passwords-setup" @click="closeAction($event)">
        <div id="setup-container">
            <div class="setup-header">
                <div class="logo"></div>
                <translate tag="h1" :say="current.title"/>
            </div>
            <ul class="setup-content" :style="getStyle">
                <encryption id="setup-slide-encryption"
                            class="slide"
                            :is-current="currentId === 1"
                            v-on:status="updateStatus"
                            v-on:continue="nextSlide"
                            v-if="hasSlide('encryption')"/>
                <admin-settings id="setup-slide-admin-settings" class="slide" v-if="hasSlide('admin-settings')"/>
                <user-settings id="setup-slide-user-settings" class="slide" v-if="hasSlide('user-settings')"/>
                <get-help id="setup-slide-get-help"
                          class="slide"
                          v-on:redirect="openSection($event, false)"
                          v-if="hasSlide('get-help')"/>
                <keep-order id="setup-slide-keep-order" class="slide" v-if="hasSlide('keep-order')"/>
                <integrations id="setup-slide-integrations"
                              class="slide"
                              v-on:redirect="openSection"
                              v-if="hasSlide('integrations')"/>
            </ul>
            <div class="setup-navigation">
                <translate tag="div" say="Skip" class="skip" @click="nextSlide" v-if="showSkipButton"/>
                <translate tag="div"
                           :say="current.action.label"
                           class="continue"
                           :title="current.action.title"
                           :class="current.action.class"
                           @click="current.action.click"
                           v-if="showCustomButton"/>
                <translate tag="div" :say="continueText" class="continue" @click="nextSlide" v-if="!showCustomButton"/>
            </div>
        </div>
    </div>
</template>

<script>
    import API from '@js/Helper/api';
    import router from '@js/Helper/router';
    import Translate from '@vue/Components/Translate';
    import Start from '@vue/Dialog/SetupDialog/Start';
    import GetHelp from '@vue/Dialog/SetupDialog/GetHelp';
    import KeepOrder from '@vue/Dialog/SetupDialog/KeepOrder';
    import SettingsService from '@js/Services/SettingsService';
    import Encryption from '@vue/Dialog/SetupDialog/Encryption';
    import Integrations from '@vue/Dialog/SetupDialog/Integrations';
    import UserSettings from '@/vue/Dialog/SetupDialog/UserSettings';
    import AdminSettings from '@vue/Dialog/SetupDialog/AdminSettings';

    export default {
        components: {UserSettings, Integrations, Encryption, Translate, KeepOrder, GetHelp, Start, AdminSettings},

        props: {
            enableSlides: {
                type: Array,
                default() {
                    let slides = ['user-settings', 'get-help', 'integrations'];
                    if(!API.hasEncryption) {
                        slides.splice(1, 0, 'encryption');
                    }
                    if(OC.isUserAdmin()) {
                        let pos = slides.indexOf('user-settings');
                        slides.splice(pos, 0, 'admin-settings');
                    }
                    return slides;
                }
            },
            closable    : {
                type   : Boolean,
                default: false
            },
            redirect    : {
                type   : Boolean,
                default: true
            },
            _close      : {
                type: Function
            }
        },

        data() {
            let slides = [
                {
                    title: 'A safe home for your passwords',
                    id   : 'start'
                },
                {
                    title    : 'Keep your passwords secret',
                    skippable: true,
                    action   : null,
                    id       : 'encryption'
                },
                {
                    title: 'Customize your experience',
                    id   : 'admin-settings'
                },
                {
                    title: 'Customize your experience',
                    id   : 'user-settings'
                },
                {
                    title: 'Bring order to your passwords',
                    id   : 'keep-order'
                },
                {
                    title: 'Find help when you need it',
                    id   : 'get-help'
                },
                {
                    title: 'Get connected',
                    id   : 'integrations'
                }
            ];

            for(let i = 0; i < slides.length; i++) {
                if(this.enableSlides.indexOf(slides[i].id) === -1) {
                    slides.splice(i, 1);
                    i--;
                }
            }


            return {
                current       : slides[0],
                currentId     : 0,
                isDefaultRoute: true,
                route         : {path: '/'},
                slides
            };
        },
        computed: {
            getStyle() {
                if(this.currentId < 1) return '';
                if(window.innerWidth > 900) {
                    return `transform: translateX(-${this.currentId * 900}px);`;
                }

                return `transform: translateX(-${this.currentId * 100}vw);`;
            },
            continueText() {
                return this.currentId === this.slides.length - 1 ? 'Close':'Continue';
            },
            showSkipButton() {
                return this.current.skippable;
            },
            showCustomButton() {
                return this.current.hasOwnProperty('action') && this.current.action !== null;
            }
        },
        methods : {
            hasSlide(id) {
                return this.enableSlides.indexOf(id) !== -1;
            },
            goToSlide(id) {
                if(!this.slides.hasOwnProperty(id)) return;

                this.current = this.slides[id];
                this.currentId = id;
            },
            nextSlide() {
                if(this.currentId < this.slides.length - 1) {
                    this.goToSlide(this.currentId + 1);
                } else {
                    this.closeWizard();
                }
            },
            openSection($event, close = true) {
                this.route = $event;
                this.isDefaultRoute = false;
                if(close) this.closeWizard();
            },
            updateStatus(e) {
                if(e.id === this.current.id) {
                    if(e.hasOwnProperty('skippable')) this.current.skippable = e.skippable === true;
                    if(e.hasOwnProperty('action')) this.current.action = e.action;
                }
            },
            closeWizard() {
                SettingsService.set('client.setup.initialized', true);
                this.$destroy();
                this.$el.parentNode.removeChild(this.$el);
                if(this.redirect || !this.isDefaultRoute) router.push(this.route);
                if(this._close) { this._close(); }
            },
            closeAction($e) {
                if(this.closable && $e.target.id === 'passwords-setup' && this.current.skippable) {
                    this.closeWizard();
                }
            }
        }
    };
</script>

<style lang="scss">
#passwords-setup {
    display          : flex;
    align-items      : center;
    justify-content  : center;
    flex-wrap        : wrap;
    position         : fixed;
    top              : 0;
    left             : 0;
    width            : 100%;
    height           : 100%;
    background-color : rgba(0, 0, 0, 0.7);
    z-index          : 3001;
    backdrop-filter  : blur(3px);

    #setup-container {
        border-radius    : var(--border-radius-large);
        overflow         : hidden !important;
        max-width        : 900px;
        width            : 100%;
        background-color : var(--color-main-background);
        position         : relative;

        @media (max-width : 900px) {
            width         : 100vw;
            height        : 100vh;
            border-radius : 0;
            display       : flex;
            flex-wrap     : wrap;
        }
    }

    .setup-header {
        padding         : 1.25rem;
        background      : var(--color-primary) var(--image-background-default) no-repeat 50% 50%;
        background-size : cover;
        color           : var(--color-primary-text);
        text-align      : center;
        width           : 100%;

        .logo {
            height          : 120px;
            background      : var(--pw-image-logo) no-repeat center;
            background-size : contain;
        }

        h1 {
            font-size   : 3rem;
            line-height : 3rem;
            margin-top  : 1rem;
        }

        @media (max-width : 900px) {
            padding         : 1rem;
            align-items     : center;
            display         : flex;
            flex-direction  : column;
            justify-content : center;

            .logo {
                width : 120px;
            }
        }

        @media (max-width : $width-extra-small) {
            .logo {
                height : 60px;
                width  : 60px;
            }

            h1 {
                font-size   : 2rem;
                line-height : 2rem;
                margin-top  : 0.5rem;
            }
        }
    }

    .setup-content {
        width       : 10000px;
        transition  : transform 0.25s ease-in-out;
        display     : flex;
        align-items : stretch;

        .slide {
            width      : 900px;
            min-height : 450px;
        }

        @media (max-width : 900px) {
            .slide {
                width : 100vw;
            }
        }
    }

    .setup-navigation {
        width : 100%;

        .skip,
        .continue {
            background-color : var(--color-primary-element);
            padding          : 0.75rem;
            border-radius    : var(--border-radius-pill);
            color            : var(--color-primary-text);
            cursor           : pointer;
            display          : inline-block;
            transition       : opacity 0.5s ease-in-out;
            position         : absolute;
            right            : .5rem;
            bottom           : .5rem;

            &.continue {
                float : right;
            }

            &.skip {
                background-color : rgba(0, 0, 0, 0);
                color            : var(--color-text-lighter);
                right            : auto;
                left             : .5rem;
            }

            &.disabled {
                opacity : 0.5;
                cursor  : default;
            }
        }

        @media (max-width : 900px) {
            position : absolute;
            bottom   : 0;
        }
    }
}

body {
    &[data-custom-color="false"] #passwords-setup .setup-header {
        background-image      : var(--image-background-default), linear-gradient(40deg, #0082c9 0%, #30b6ff 100%);
        background-repeat     : repeat;
        background-size       : contain;
        background-attachment : fixed;
    }

    &[data-custom-color="true"] #passwords-setup .setup-header {
        background-image : linear-gradient(40deg, var(--color-primary-element) 0%, var(--color-primary-element-light) 100%);
    }
}
</style>