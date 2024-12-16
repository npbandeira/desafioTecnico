<template>
    <v-container>

        <!-- Tabela de usuários -->
        <v-btn color="primary" @click="openDialog" class="mb-4">Adicionar Usuário</v-btn>

        <v-data-table :headers="headers" :items="usuario" item-key="id" class="elevation-1" dense>
            <template v-slot:item.actions="{ item }">
                <v-btn color="blue" @click="editUser(item)" small class="mx-2">Editar</v-btn>
                <v-btn color="red" @click="deleteUser(item.id)" small class="mx-2">Excluir</v-btn>
            </template>
        </v-data-table>

        <!-- Dialog para adicionar/editar usuário -->
        <v-dialog v-model="dialog" max-width="500px">
            <v-card>
                <v-card-title>{{ isEdit ? 'Editar Usuário' : 'Adicionar Usuário' }}</v-card-title>
                <v-card-text>
                    <v-form ref="form" v-model="valid">
                        <v-text-field v-model="user.name" label="Nome" :rules="[rules.required('Nome é obrigatório')]"
                            required class="mb-4" />
                        <v-text-field v-model="user.cpf" label="CPF"
                            :rules="[rules.required('CPF é obrigatório'), rules.cpf]" required class="mb-4" />
                        <v-text-field v-model="user.email" label="E-mail"
                            :rules="[rules.required('E-mail é obrigatório'), rules.email]" required class="mb-4" />
                        <v-text-field v-model="user.password" label="Senha" type="password"
                            :rules="[rules.required('Senha é obrigatória'), rules.minLength(8)]" required
                            class="mb-4" />
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="green darken-1" text @click="saveUser">{{ isEdit ? 'Salvar' : 'Adicionar' }}</v-btn>
                    <v-btn color="red darken-1" text @click="closeDialog">Cancelar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-snackbar v-model="snackbar.show" :color="snackbar.color" timeout="3000">
            {{ snackbar.message }}
        </v-snackbar>
    </v-container>
</template>

<script>
import axios from 'axios'

export default {
    data() {
        return {
            dialog: false,
            valid: false,
            isEdit: false,
            user: {
                id: null,
                name: '',
                cpf: '',
                email: '',
                password: ''
            },
            usuario: [],
            headers: [
                { text: 'Nome', align: 'start', key: 'name' },
                { text: 'CPF', value: 'cpf' },
                { text: 'Email', value: 'email' },
                { text: 'Ações', value: 'actions', sortable: false }
            ],
            rules: {
                required: (msg) => (v) => !!v || msg,
                email: (v) => /.+@.+\..+/.test(v) || 'E-mail inválido',
                cpf: (v) => /^\d{11}$/.test(v) || 'CPF deve conter 11 dígitos',
                minLength: (min) => (v) => (v && v.length >= min) || `Mínimo de ${min} caracteres`
            },
            snackbar: {
                show: false,
                message: '',
                color: ''
            },
            errorMessage: '', // Variável para armazenar a mensagem de erro
            fieldErrors: {} // Para armazenar erros de campo específicos
        }
    },
    methods: {
        openDialog() {
            this.isEdit = false
            this.user = { id: null, name: '', cpf: '', email: '', password: '' }
            this.dialog = true
        },
        closeDialog() {
            this.dialog = false
        },
        saveUser() {
            if (this.$refs.form.validate()) {
                if (this.isEdit) {
                    this.updateUser()
                } else {
                    this.createUser()
                }
            }
        },
        createUser() {
            axios.post('http://localhost:8000/api/usuario', this.user)
                .then(response => {
                    this.usuario.push(response.data)
                    this.showSnackbar('Usuário adicionado com sucesso!', 'success')
                    this.closeDialog()
                })
                .catch(error => {
                    this.handleError(error)
                })
        },
        editUser(user) {
            this.isEdit = true
            this.user = { ...user }
            this.dialog = true
        },
        updateUser() {
            axios.put(`http://localhost:8000/api/usuario/${this.user.id}`, this.user)
                .then(response => {
                    const index = this.usuario.findIndex(u => u.id === this.user.id)
                    if (index !== -1) {
                        this.usuario[index] = response.data
                    }
                    this.showSnackbar('Usuário atualizado com sucesso!', 'success')
                    this.closeDialog()
                })
                .catch(error => {
                    this.handleError(error)
                })
        },
        deleteUser(id) {
            if (confirm('Tem certeza que deseja excluir este usuário?')) {
                axios.delete(`http://localhost:8000/api/usuario/${id}`)
                    .then(() => {
                        this.usuario = this.usuario.filter(user => user.id !== id)
                        this.showSnackbar('Usuário excluído com sucesso!', 'success')
                    })
                    .catch(error => {
                        this.handleError(error)
                    })
            }
        },
        handleError(error) {
            if (error.response && error.response.data) {
                const data = error.response.data
                this.errorMessage = data.message || 'Erro ao processar a solicitação.'
                this.fieldErrors = data.errors || {}
                this.showSnackbar(this.errorMessage, 'error')
            } else {
                this.errorMessage = 'Erro inesperado. Tente novamente mais tarde.'
                this.showSnackbar(this.errorMessage, 'error')
            }
        },
        showSnackbar(message, color) {
            this.snackbar.message = message
            this.snackbar.color = color
            this.snackbar.show = true
        }
    },
    mounted() {
        axios.get('http://localhost:8000/api/usuario')
            .then(response => {
                this.usuario = response.data
            })
            .catch(error => {
                this.handleError(error)
            })
    }
}
</script>

<style scoped>
.v-btn {
    margin-bottom: 10px;
}

.v-data-table {
    margin-top: 20px;
}

.v-text-field {
    margin-bottom: 20px;
}

.v-card-title {
    font-weight: bold;
}

.v-alert {
    margin-bottom: 20px;
}
</style>