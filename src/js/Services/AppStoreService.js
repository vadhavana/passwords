/*
 * @copyright 2021 Passwords App
 *
 * @author Marius David Wieschollek
 * @license AGPL-3.0
 *
 * This file is part of the Passwords App
 * created by Marius David Wieschollek.
 */

import Localisation    from "@js/Classes/Localisation";
import SettingsService from "@js/Services/SettingsService";
import Logger from "@js/Classes/Logger";

export default new class AppStoreService {

    constructor() {
        this._storeData = null;
        this._appsUrl = `${SettingsService.get('server.handbook.url')}_appstore/apps-v1.json`;
    }

    async getItems(section) {
        let data = await this._loadStoreData();
        if(!data.hasOwnProperty(section)) return [];

        let baseUrl = this._appsUrl.substr(this._appsUrl, this._appsUrl.lastIndexOf('/') + 1),
            items   = [];

        for(let item of data[section].items) {
            let downloads = [];

            for(let download of item.downloads) {
                downloads.push(
                    {
                        label: this._resolveTranslatableProperty(download.label),
                        url  : download.url
                    }
                );
            }

            items.push(
                {
                    id         : `${section}-${item.id}`,
                    label      : this._resolveTranslatableProperty(item.label),
                    description: this._resolveTranslatableProperty(item.description),
                    logo       : item.hasOwnProperty('logo') ? new URL(item.logo, baseUrl) : null,
                    official   : item.official,
                    author     : {
                        name    : item.author.name,
                        homepage: item.author.homepage
                    },
                    passlink   : {
                        enabled : item.passlink.enabled,
                        link    : item.passlink.enabled && item.passlink.link,
                        qrcode  : item.passlink.enabled && item.passlink.qrcode,
                        protocol: item.passlink.enabled ? item.passlink.protocol:null
                    },
                    links      : {
                        homepage: item.links.hasOwnProperty('homepage') ? item.links.homepage : null,
                        download: item.links.download,
                        sources : item.links.sources
                    },
                    features   : {
                        encryption: item.features.encryption
                    },
                    recommended: item.recommended,
                    downloads
                }
            );
        }

        return items;
    }

    async getSections() {
        let data     = await this._loadStoreData(),
            sections = [];

        for(let key in data) {
            if(!data.hasOwnProperty(key)) continue;
            let section = data[key];

            sections.push(
                {
                    id   : key,
                    label: this._resolveTranslatableProperty(section.label),
                    style: section.style,
                    icon : section.icon
                }
            );
        }

        return sections;
    }

    _resolveTranslatableProperty(property) {
        if(property.hasOwnProperty(Localisation.language)) {
            return property[Localisation.language];
        }
        if(property.hasOwnProperty(Localisation.baseLanguage)) {
            return property[Localisation.baseLanguage];
        }
        if(property.hasOwnProperty('en')) {
            return property.en;
        }
        return '';
    }

    async _loadStoreData() {
        if(this._storeData !== null) return this._storeData;
        let response = await fetch(new Request(this._appsUrl, {credentials: 'omit', referrerPolicy: 'no-referrer'}));

        if(response.ok) {
            this._storeData = await response.json();
            return this._storeData;
        } else {
            Logger.error(response);
            throw new Error(`${response.status} - ${response.statusText}`);
        }
    }
};