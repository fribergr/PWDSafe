<template>
    <div class="max-w-sm w-full card my-2 mx-2 bg-white shadow flex space-between flex-col">
        <div class="card-body flex-1 p-4">
            <h5 class="text-xl">{{ credential.site }}</h5>
            <h6 class="mb-2 text-gray-700">{{ credential.username }}</h6>
            <p>{{ credential.notes }}</p>
        </div>
        <div class="card-footer bg-gray-100 p-4 border-t">
            <div class="flex justify-between">
                <div>
                    <span v-if="showgroupname">{{ groupname }}</span>
                    <span v-else>&nbsp;</span>
                </div>
                <div class="flex">
                    <pwdsafe-modal v-on:modal-open="getPassword" v-on:modal-close="resetData">
                        <template v-slot:trigger>
                            <pwdsafe-button theme="secondary" :data-id="credential.id" classes="mr-1">
                                <i class="far fa-eye" title="Show"></i>
                            </pwdsafe-button>
                        </template>
                        <form method="post" :action="'/credential/' + credential.id" @submit.prevent="saveCredentials">
                            <input type="hidden" name="_method" value="put">
                            <div class="mb-2">
                                <pwdsafe-label for="site" class="mb-1">Site</pwdsafe-label>
                                <pwdsafe-input name="site" id="site" v-model="credentialint.site"/>
                            </div>
                            <div class="mb-2">
                                <pwdsafe-label for="username" class="mb-1">Username</pwdsafe-label>
                                <pwdsafe-input name="username" id="username" v-model="credentialint.username"/>
                            </div>
                            <div class="mb-2">
                                <pwdsafe-label for="password" class="mb-1">Password</pwdsafe-label>
                                <pwdsafe-input name="password" id="password" v-model="password"/>
                            </div>
                            <div class="mb-2">
                                <pwdsafe-label for="notes" class="mb-1">Notes</pwdsafe-label>
                                <pwdsafe-textarea name="notes" id="notes" @changed="credentialint.notes = $event">{{ credentialint.notes }}</pwdsafe-textarea>
                            </div>
                            <div class="mb-2">
                                <pwdsafe-label for="notes" class="mb-1">Move to group</pwdsafe-label>
                                <pwdsafe-select name="group" id="group"
                                                @selected="credentialint.groupid = parseInt($event.target.value)">
                                    <option v-for="group in groups" :value="group.id"
                                            :selected="group.id === credential.groupid">{{ group.name }}
                                    </option>
                                </pwdsafe-select>
                            </div>

                            <div class="flex justify-between py-2">
                                <pwdsafe-button btntype="a" :href="'/credential/' + credential.id" theme="danger">Delete</pwdsafe-button>
                                <div>
                                    <pwdsafe-button type="submit">Save</pwdsafe-button>
                                </div>
                            </div>
                        </form>
                    </pwdsafe-modal>
                    <pwdsafe-button theme="secondary" classes="mr-1" @click.native="copyPwd">
                        <i class="far fa-copy" title="Copy to clipboard"></i>
                    </pwdsafe-button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        credential: {
            type: Object
        },
        groups: {
            type: Array
        },
        showgroupname: {
            type: Boolean,
            default: false
        },
        groupname: {
            type: String,
            default: ''
        }
    },
    data() {
        return {
            password: '',
            credentialint: this.credential
        }
    },
    methods: {
        getPassword: function () {
            axios.get('/pwdfor/' + this.credential.id).then((response) => {
                this.password = response.data.pwd
            });
        },
        copyPwd: function () {
            axios.get('/pwdfor/' + this.credential.id)
                .then((response) => {
                    this.$clipboard(response.data.pwd);
                });
        },
        resetData: function () {
            this.password = '';
        },
        saveCredentials: function() {
            axios.put('/credential/' + this.credential.id, {
                creds: this.credentialint.site,
                credu: this.credentialint.username,
                credp: this.password,
                credn: this.credentialint.notes,
                currentgroupid: this.credentialint.groupid
            }).then(() => {
                window.location.reload();
            })
        }
    }
}
</script>
