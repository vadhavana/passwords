/*
 * @copyright 2022 Passwords App
 *
 * @author Marius David Wieschollek
 * @license AGPL-3.0
 *
 * This file is part of the Passwords App
 * created by Marius David Wieschollek.
 */

import AbstractTask from "@js/Tasks/AbstractTask";

export default class ShareFolderTask extends AbstractTask {

    constructor(folder) {
        super();
        this._folder = folder;
    }

    run() {
        if(!this._folder.shared) {

        }
    }
}