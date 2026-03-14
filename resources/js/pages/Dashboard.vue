<script setup lang="ts">
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import { Pencil, Plus, Search, Trash2, Package } from 'lucide-vue-next';
import { ref, computed  } from 'vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import type { BreadcrumbItem } from '@/types';

interface Product {
    id: number;
    name: string;
    slug: string;
    description: string | null;
    price: number;
    stock: number;
    image_url: string | null;
    images?: string[];
}

const props = defineProps<{
    products: Product[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
    },
];

const searchQuery = ref('');
const showCreateDialog = ref(false);
const showEditDialog = ref(false);
const showDeleteDialog = ref(false);
const selectedProduct = ref<Product | null>(null);

const form = useForm<{
    name: string;
    description: string;
    price: number;
    stock: number;
    images: File[];
}>({
    name: '',
    description: '',
    price: 0,
    stock: 0,
    images: [],
});

const page = usePage();
const flashSuccess = () => (page.props.flash as any)?.success as string | undefined;

const filteredProducts = () => {
    if (!searchQuery.value) return props.products;
    const query = searchQuery.value.toLowerCase();
    return props.products.filter(
        (p) =>
            p.name.toLowerCase().includes(query) ||
            p.description?.toLowerCase().includes(query)
    );
};

const formatPrice = (price: number) => {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL',
    }).format(price);
};

const openCreateDialog = () => {
    form.reset();
    showCreateDialog.value = true;
};

const openEditDialog = (product: Product) => {
    selectedProduct.value = product;
    form.name = product.name;
    form.description = product.description || '';
    form.price = product.price;
    form.stock = product.stock;
    form.images = [];
    showEditDialog.value = true;
};

const openDeleteDialog = (product: Product) => {
    selectedProduct.value = product;
    showDeleteDialog.value = true;
};

const handleFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement | null;
    const files = Array.from(target?.files ?? []) as File[];
    console.log(files);

    const allowed = new Set(['image/jpeg', 'image/png', 'image/webp', 'image/avif']);
    const invalid = files.filter((f) => !allowed.has(f.type));

    if (invalid.length > 0) {
        form.setError('images', 'Cada arquivo precisa ser uma imagem (jpeg, png, webp ou avif).');
        form.images = [];
        if (target) target.value = '';
        return;
    }

    form.clearErrors('images');
    form.images = files;
};

const createProduct = () => {
    form.post('/produtos', {
        forceFormData: true,
        onSuccess: () => {
            showCreateDialog.value = false;
            form.reset();
        },
    });
};

const updateProduct = () => {
    if (!selectedProduct.value) return;

    form.put(`/produtos/${selectedProduct.value.id}`, {
        forceFormData: true,
        onSuccess: () => {
            showEditDialog.value = false;
            form.reset();
        },
    });
};

const deleteProduct = () => {
    if (!selectedProduct.value) return;

    router.delete(`/produtos/${selectedProduct.value.id}`, {
        onSuccess: () => {
            showDeleteDialog.value = false;
            selectedProduct.value = null;
        },
    });
};

const uniqueErrorMessages = computed(() => {
    const values = Object.values(form.errors || {});
    return Array.from(new Set(values));
});
</script>

<template>
    <Head title="Dashboard - Admin" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6">
            <div v-if="flashSuccess()" class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                {{ flashSuccess() }}
            </div>

            <div v-if="form.hasErrors" class="rounded-lg border border-destructive/30 bg-destructive/10 px-4 py-3 text-sm">
                <div class="font-medium text-destructive">Corrija os erros abaixo:</div>
                <ul class="mt-2 list-disc pl-5 text-destructive">
                    <li v-for="(msg, idx) in uniqueErrorMessages" :key="idx">{{ msg }}</li>
                </ul>
            </div>

            <!-- Header -->
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight">Produtos</h1>
                    <p class="text-muted-foreground">
                        Gerencie os produtos da sua loja
                    </p>
                </div>
                <Button @click="openCreateDialog">
                    <Plus class="mr-2 h-4 w-4" />
                    Novo Produto
                </Button>
            </div>

            <!-- Stats -->
            <div class="grid gap-4 md:grid-cols-3">
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between pb-2">
                        <CardTitle class="text-sm font-medium">Total de Produtos</CardTitle>
                        <Package class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ products.length }}</div>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between pb-2">
                        <CardTitle class="text-sm font-medium">Em Estoque</CardTitle>
                        <Package class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">
                            {{ products.filter(p => p.stock > 0).length }}
                        </div>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between pb-2">
                        <CardTitle class="text-sm font-medium">Sem Estoque</CardTitle>
                        <Package class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">
                            {{ products.filter(p => p.stock === 0).length }}
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Search -->
            <div class="relative max-w-sm">
                <Search class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                <Input
                    v-model="searchQuery"
                    placeholder="Buscar produtos..."
                    class="pl-10"
                />
            </div>

            <!-- Products Table -->
            <Card>
                <CardContent class="p-0">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="border-b bg-muted/50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-sm font-medium">Produto</th>
                                    <th class="px-4 py-3 text-left text-sm font-medium">Preço</th>
                                    <th class="px-4 py-3 text-left text-sm font-medium">Estoque</th>
                                    <th class="px-4 py-3 text-right text-sm font-medium">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="filteredProducts().length === 0">
                                    <td colspan="4" class="px-4 py-8 text-center text-muted-foreground">
                                        Nenhum produto encontrado
                                    </td>
                                </tr>
                                <tr
                                    v-for="product in filteredProducts()"
                                    :key="product.id"
                                    class="border-b transition-colors hover:bg-muted/50"
                                >
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            <div class="h-12 w-12 overflow-hidden rounded-lg bg-muted">
                                                <img
                                                    v-if="product.image_url"
                                                    :src="product.image_url"
                                                    :alt="product.name"
                                                    class="h-full w-full object-cover"
                                                />
                                                <div v-else class="flex h-full w-full items-center justify-center">
                                                    <Package class="h-5 w-5 text-muted-foreground" />
                                                </div>
                                            </div>
                                            <div>
                                                <div class="font-medium">{{ product.name }}</div>
                                                <div class="text-sm text-muted-foreground line-clamp-1">
                                                    {{ product.description || 'Sem descrição' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 font-medium">
                                        {{ formatPrice(product.price) }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <span
                                            :class="[
                                                'inline-flex rounded-full px-2 py-1 text-xs font-medium',
                                                product.stock > 5 ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' :
                                                product.stock > 0 ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300' :
                                                'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300'
                                            ]"
                                        >
                                            {{ product.stock }} un.
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-end gap-2">
                                            <Button variant="ghost" size="sm" @click="openEditDialog(product)">
                                                <Pencil class="h-4 w-4" />
                                            </Button>
                                            <Button variant="ghost" size="sm" @click="openDeleteDialog(product)">
                                                <Trash2 class="h-4 w-4 text-destructive" />
                                            </Button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </CardContent>
            </Card>
        </div>

        <!-- Create Dialog -->
        <Dialog v-model:open="showCreateDialog">
            <DialogContent class="sm:max-w-[425px]">
                <DialogHeader>
                    <DialogTitle>Novo Produto</DialogTitle>
                    <DialogDescription>
                        Preencha as informações do produto
                    </DialogDescription>
                </DialogHeader>
                <form @submit.prevent="createProduct" class="space-y-4">
                    <div class="space-y-2">
                        <Label for="name">Nome</Label>
                        <Input id="name" v-model="form.name" required />
                    </div>
                    <div class="space-y-2">
                        <Label for="description">Descrição</Label>
                        <Input id="description" v-model="form.description" />
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <Label for="price">Preço (R$)</Label>
                            <Input id="price" v-model="form.price" type="number" step="0.01" min="0" required />
                        </div>
                        <div class="space-y-2">
                            <Label for="stock">Estoque</Label>
                            <Input id="stock" v-model="form.stock" type="number" min="0" required />
                        </div>
                    </div>
                    <div class="space-y-2">
                        <Label for="images">Imagens</Label>
                        <Input id="images" type="file" accept="image/*" multiple @change="handleFileChange" />
                        <p class="text-xs text-muted-foreground">Você pode selecionar várias imagens.</p>
                    </div>
                    <DialogFooter>
                        <Button type="button" variant="outline" @click="showCreateDialog = false">
                            Cancelar
                        </Button>
                        <Button type="submit" :disabled="form.processing">
                            {{ form.processing ? 'Salvando...' : 'Criar Produto' }}
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>

        <!-- Edit Dialog -->
        <Dialog v-model:open="showEditDialog">
            <DialogContent class="sm:max-w-[425px]">
                <DialogHeader>
                    <DialogTitle>Editar Produto</DialogTitle>
                    <DialogDescription>
                        Atualize as informações do produto
                    </DialogDescription>
                </DialogHeader>
                <form @submit.prevent="updateProduct" class="space-y-4">
                    <div class="space-y-2">
                        <Label for="edit-name">Nome</Label>
                        <Input id="edit-name" v-model="form.name" required />
                    </div>
                    <div class="space-y-2">
                        <Label for="edit-description">Descrição</Label>
                        <Input id="edit-description" v-model="form.description" />
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <Label for="edit-price">Preço (R$)</Label>
                            <Input id="edit-price" v-model="form.price" type="number" step="0.01" min="0" required />
                        </div>
                        <div class="space-y-2">
                            <Label for="edit-stock">Estoque</Label>
                            <Input id="edit-stock" v-model="form.stock" type="number" min="0" required />
                        </div>
                    </div>
                    <div class="space-y-2">
                        <Label for="edit-image">Novas Imagens</Label>
                        <Input id="edit-image" type="file" accept="image/*" multiple @change="handleFileChange" />
                        <p class="text-xs text-muted-foreground">Você pode selecionar várias imagens.</p>
                    </div>
                    <DialogFooter>
                        <Button type="button" variant="outline" @click="showEditDialog = false">
                            Cancelar
                        </Button>
                        <Button type="submit" :disabled="form.processing">
                            {{ form.processing ? 'Salvando...' : 'Salvar Alterações' }}
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>

        <!-- Delete Dialog -->
        <Dialog v-model:open="showDeleteDialog">
            <DialogContent class="sm:max-w-[425px]">
                <DialogHeader>
                    <DialogTitle>Excluir Produto</DialogTitle>
                    <DialogDescription>
                        Tem certeza que deseja excluir "{{ selectedProduct?.name }}"? Esta ação não pode ser desfeita.
                    </DialogDescription>
                </DialogHeader>
                <DialogFooter>
                    <Button type="button" variant="outline" @click="showDeleteDialog = false">
                        Cancelar
                    </Button>
                    <Button type="button" variant="destructive" @click="deleteProduct">
                        Excluir
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
