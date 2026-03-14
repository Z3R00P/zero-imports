<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft, MessageCircle, Package, ChevronLeft, ChevronRight } from 'lucide-vue-next';
import { computed, ref } from 'vue';

import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';

interface Product {
    id: number;
    name: string;
    slug: string;
    description: string | null;
    price: number;
    stock: number;
    image_url: string | null;
    images_urls?: string[];
}

const props = defineProps<{
    product: Product;
}>();

const formatPrice = (price: number) => {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL',
    }).format(price);
};

const whatsappNumber = '5567991765813';

const getWhatsAppLink = (product: Product) => {
    const message = encodeURIComponent(
        `Olá! Tenho interesse no produto: ${product.name} - ${formatPrice(product.price)}`
    );
    return `https://wa.me/${whatsappNumber}?text=${message}`;
};

const allImages = computed<string[]>(() => {
    if (props.product.images_urls?.length) return props.product.images_urls;
    return props.product.image_url ? [props.product.image_url] : [];
});

const activeIndex = ref(0);

const goPrev = () => {
    if (!allImages.value.length) return;
    activeIndex.value = (activeIndex.value - 1 + allImages.value.length) % allImages.value.length;
};

const goNext = () => {
    if (!allImages.value.length) return;
    activeIndex.value = (activeIndex.value + 1) % allImages.value.length;
};

const setActive = (i: number) => {
    activeIndex.value = i;
};
</script>

<template>
    <Head :title="`${product.name} - Zero Imports`" />

    <div class="min-h-screen bg-gradient-to-b from-background to-muted/20">
        <!-- Header -->
        <header
            class="sticky top-0 z-50 w-full border-b bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60"
        >
            <div class="container mx-auto flex h-16 items-center justify-between px-4">
                <Link href="/" class="flex items-center gap-2 transition-opacity hover:opacity-80">
                    <Package class="h-8 w-8 text-primary" />
                    <span class="text-xl font-bold">Zero Imports</span>
                </Link>

                <div class="flex items-center gap-2">
                    <Button as-child variant="outline">
                        <Link href="/produtos">
                            <ArrowLeft class="mr-2 h-4 w-4" />
                            Voltar
                        </Link>
                    </Button>
                    <Button as-child>
                        <a :href="`https://wa.me/${whatsappNumber}`" target="_blank">
                            <MessageCircle class="mr-2 h-4 w-4" />
                            Contato
                        </a>
                    </Button>
                </div>
            </div>
        </header>

        <main class="container mx-auto px-4 py-10">
            <div class="grid gap-8 lg:grid-cols-2">
                <!-- Carousel / Gallery -->
                <div>
                    <div class="relative aspect-square overflow-hidden rounded-xl border bg-muted">
                        <template v-if="allImages.length">
                            <img
                                v-for="(img, i) in allImages"
                                :key="img"
                                :src="img"
                                :alt="product.name"
                                class="absolute inset-0 h-full w-full object-cover transition-all duration-500 ease-in-out"
                                :class="i === activeIndex
                                ? 'opacity-100 translate-x-0'
                                : i < activeIndex
                                    ? 'opacity-0 -translate-x-full'
                                    : 'opacity-0 translate-x-full'"
                            />
                        </template>
                        <div v-else class="flex h-full w-full items-center justify-center">
                            <Package class="h-20 w-20 text-muted-foreground/30" />
                        </div>

                        <template v-if="allImages.length > 1">
                            <button
                                type="button"
                                class="absolute left-2 top-1/2 -translate-y-1/2 rounded-full bg-background/80 p-2 shadow hover:bg-background"
                                @click="goPrev"
                                aria-label="Imagem anterior"
                            >
                                <ChevronLeft class="h-5 w-5" />
                            </button>
                            <button
                                type="button"
                                class="absolute right-2 top-1/2 -translate-y-1/2 rounded-full bg-background/80 p-2 shadow hover:bg-background"
                                @click="goNext"
                                aria-label="Próxima imagem"
                            >
                                <ChevronRight class="h-5 w-5" />
                            </button>

                            <div class="absolute bottom-3 left-1/2 flex -translate-x-1/2 gap-1.5">
                                <button
                                    v-for="i in allImages.length"
                                    :key="i"
                                    type="button"
                                    class="h-2 w-2 rounded-full"
                                    :class="i - 1 === activeIndex ? 'bg-primary' : 'bg-background/70'"
                                    @click="setActive(i - 1)"
                                    :aria-label="`Ir para imagem ${i}`"
                                />
                            </div>
                        </template>
                    </div>

                    <div v-if="allImages.length > 1" class="mt-4 grid grid-cols-5 gap-2">
                        <button
                            v-for="(img, i) in allImages"
                            :key="img + i"
                            type="button"
                            class="aspect-square overflow-hidden rounded-lg border bg-muted"
                            :class="i === activeIndex ? 'ring-2 ring-primary ring-offset-2 ring-offset-background' : ''"
                            @click="setActive(i)"
                        >
                            <img :src="img" :alt="product.name" class="h-full w-full object-cover" />
                        </button>
                    </div>
                </div>

                <!-- Details -->
                <div class="space-y-4">
                    <div>
                        <h1 class="text-3xl font-bold tracking-tight">{{ product.name }}</h1>
                        <p v-if="product.description" class="mt-2 text-muted-foreground">
                            {{ product.description }}
                        </p>
                    </div>

                    <Card>
                        <CardContent class="space-y-3 p-6">
                            <div class="flex items-baseline justify-between gap-4">
                                <div>
                                    <div class="text-sm text-muted-foreground">Preço</div>
                                    <div class="text-3xl font-bold text-primary">{{ formatPrice(product.price) }}</div>
                                </div>
                                <Badge :variant="product.stock > 0 ? 'secondary' : 'destructive'">
                                    {{ product.stock > 0 ? `${product.stock} em estoque` : 'Sem estoque' }}
                                </Badge>
                            </div>

                            <Button as-child class="w-full">
                                <a :href="getWhatsAppLink(product)" target="_blank">
                                    <MessageCircle class="mr-2 h-4 w-4" />
                                    Comprar via WhatsApp
                                </a>
                            </Button>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </main>
    </div>
</template>
