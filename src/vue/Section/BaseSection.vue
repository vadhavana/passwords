<!--
  - @copyright 2023 Passwords App
  -
  - @author Marius David Wieschollek
  - @license AGPL-3.0
  -
  - This file is part of the Passwords App
  - created by Marius David Wieschollek.
  -->

<template>
    <div id="app-content" :class="getContentClass">
        <div class="app-content-left">
            <breadcrumb
                    :showAddNew="getBreadcrumb.showAddNew"
                    :newPassword="getBreadcrumb.newPassword"
                    :newFolder="getBreadcrumb.newFolder"
                    :newTag="getBreadcrumb.newTag"
                    :folder="getBreadcrumb.folder"
                    :tag="getBreadcrumb.tag"
                    :items="getBreadcrumb.items"/>
            <div class="item-list">
                <header-line :field="sorting.field"
                             :ascending="sorting.ascending"
                             v-on:updateSorting="updateSorting($event)"
                             v-if="isNotEmpty"/>
                <folder-line :folder="folder" v-for="folder in folders" :key="folder.id" :draggable="isDraggable" v-if="!loading"/>
                <tag-line :tag="tag" v-for="tag in tags" :key="tag.id" :draggable="isDraggable" v-if="!loading"/>
                <password-line :password="password"
                               v-for="password in passwords"
                               :key="password.id"
                               :draggable="isDraggable" v-if="!loading"/>
                <footer-line :passwords="passwords" :folders="folders" :tags="tags" v-if="isNotEmpty"/>
                <empty v-if="isEmpty" :text="getEmptyText"/>
            </div>
        </div>
    </div>
</template>

<script>
    import Breadcrumb from '@vc/Breadcrumb';
    import Events from '@js/Classes/Events';
    import Utility from '@js/Classes/Utility';
    import FolderLine from '@vue/Line/Folder';
    import HeaderLine from '@vue/Line/Header';
    import FooterLine from '@vue/Line/Footer';
    import PasswordLine from '@vue/Line/Password';
    import Localisation from '@js/Classes/Localisation';
    import SearchManager from '@js/Manager/SearchManager';
    import SettingsService from '@js/Services/SettingsService';

    export default {
        components: {
            Breadcrumb,
            FolderLine,
            HeaderLine,
            FooterLine,
            PasswordLine,
            'empty': () => import(/* webpackChunkName: "EmptyContent" */ '@vc/Empty'),
            'tag-line': () => import(/* webpackChunkName: "TagLine" */ '@vue/Line/Tag'),
        },

        data() {
            return {
                passwords: [],
                folders  : [],
                tags     : [],
                loading  : true,
                sorting  : {
                    field    : SettingsService.get('local.ui.sorting.field', 'label'),
                    ascending: SettingsService.get('local.ui.sorting.ascending', true)
                },
                ui       : {
                    showTags: SettingsService.get('client.ui.list.tags.show', false) && window.innerWidth > 360
                },
                search   : SearchManager.status
            };
        },

        created() {
            this.refreshView();
            Events.on('data.changed', this.refreshView);
            SearchManager.clearDatabase();
        },

        beforeDestroy() {
            Events.off('data.changed', this.refreshView);
            SearchManager.clearDatabase();
        },

        computed: {
            getContentClass() {
                let classes = {
                        'loading': this.loading
                    },
                    section = `section-${this.$route.name.toLowerCase()}`;
                classes[section] = true;

                return classes;
            },
            isNotEmpty() {
                return !this.loading && !this.isEmpty;
            },
            isEmpty() {
                if(this.loading) return false;
                if(this.search.active && this.search.total === 0) return true;

                return !this.passwords.length && !this.folders.length && !this.tags.length;
            },
            getEmptyText() {
                if(this.search.active) {
                    return Localisation.translate(
                        'We could not find anything for "{query}"',
                        {query: this.search.query}
                    );
                }

                return undefined;
            },
            getBreadcrumb() {
                return {};
            },
            isDraggable() {
                return false;
            }
        },

        methods: {
            updateSorting($event) {
                this.sorting = $event;
                SettingsService.set('local.ui.sorting.field', $event.field);
                SettingsService.set('local.ui.sorting.ascending', $event.ascending);

                if(this.passwords) {
                    this.passwords =
                        Utility.sortApiObjectArray(
                            this.passwords,
                            this.getPasswordsSortingField(),
                            this.sorting.ascending
                        );
                }
                if(this.folders) {
                    this.folders =
                        Utility.sortApiObjectArray(this.folders, this.sorting.field, this.sorting.ascending);
                }
                if(this.tags) {
                    this.tags =
                        Utility.sortApiObjectArray(this.tags, this.sorting.field, this.sorting.ascending);
                }
            },
            updatePasswordList(passwords) {
                this.loading = false;
                this.passwords =
                    Utility.sortApiObjectArray(passwords, this.getPasswordsSortingField(), this.sorting.ascending);
            },
            updateFolderList(folders) {
                this.loading = false;
                this.folders = Utility.sortApiObjectArray(folders, this.sorting.field, this.sorting.ascending);
            },
            updateTagList(tags) {
                this.loading = false;
                this.tags = Utility.sortApiObjectArray(tags, this.sorting.field, this.sorting.ascending);
            },
            getPasswordsSortingField() {
                let sortingField = this.sorting.field === 'label' ? SettingsService.get(
                    'client.ui.password.field.sorting'):this.sorting.field;
                if(sortingField === 'byTitle') sortingField = SettingsService.get('client.ui.password.field.title');
                return sortingField;
            }
        },
        watch  : {
            passwords(passwords) {
                let db = {passwords, folders: this.folders, tags: this.tags};
                SearchManager.setDatabase(db);
            },
            tags(tags) {
                let db = {passwords: this.passwords, folders: this.folders, tags};
                SearchManager.setDatabase(db);
            },
            folders(folders) {
                let db = {passwords: this.passwords, folders, tags: this.tags};
                SearchManager.setDatabase(db);
            }
        }
    };
</script>

<style lang="scss">
#app-content {
    height                     : calc(100vh - var(--header-height));
    overflow-y                 : initial;
    overflow-x                 : initial;
    border-top-right-radius    : var(--body-container-radius);
    border-bottom-right-radius : var(--body-container-radius);
}
</style>