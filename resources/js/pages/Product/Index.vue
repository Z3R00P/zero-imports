<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Package, ArrowLeft, MessageCircle, ChevronLeft, ChevronRight } from 'lucide-vue-next';
import { ref } from 'vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';

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

defineProps<{
    products: Product[];
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

const activeImageIndexById = ref<Record<number, number>>({});

const productImages = (product: Product) => {
    if (product.images_urls?.length) return product.images_urls;
    return product.image_url ? [product.image_url] : [];
};

const activeIndex = (product: Product) => {
    return activeImageIndexById.value[product.id] ?? 0;
};

const setActiveIndex = (product: Product, index: number) => {
    activeImageIndexById.value = {
        ...activeImageIndexById.value,
        [product.id]: index,
    };
};

const prevImage = (product: Product) => {
    const imgs = productImages(product);
    if (imgs.length <= 1) return;
    const next = (activeIndex(product) - 1 + imgs.length) % imgs.length;
    setActiveIndex(product, next);
};

const nextImage = (product: Product) => {
    const imgs = productImages(product);
    if (imgs.length <= 1) return;
    const next = (activeIndex(product) + 1) % imgs.length;
    setActiveIndex(product, next);
};
</script>

<template>
    <Head title="Produtos - Zero Imports">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>

    <div class="min-h-screen bg-gradient-to-b from-background to-muted/20">
        <!-- Header -->
        <header
            class="sticky top-0 z-50 w-full border-b bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60"
        >
            <div
                class="container mx-auto flex h-16 items-center justify-between px-4"
            >
                <Link href="/" class="flex items-center gap-2 transition-opacity hover:opacity-80">
                    <Package class="h-8 w-8 text-primary" />
                    <span class="text-xl font-bold">Zero Imports</span>
                </Link>

                <nav class="hidden items-center gap-6 md:flex">
                    <Link
                        href="/"
                        class="text-sm font-medium text-muted-foreground transition-colors hover:text-primary"
                    >
                        Início
                    </Link>
                    <Link
                        href="/produtos"
                        class="text-sm font-medium text-primary"
                    >
                        Produtos
                    </Link>
                </nav>

                <div class="flex items-center gap-2">
                    <Button as-child>
                        <a :href="`https://wa.me/${whatsappNumber}`" target="_blank">
                            <MessageCircle class="mr-2 h-4 w-4" />
                            Contato
                        </a>
                    </Button>
                </div>
            </div>
        </header>

        <!-- Hero -->
        <section class="container mx-auto px-4 py-12 text-center">
            <h1 class="mb-4 text-3xl font-bold tracking-tight sm:text-4xl md:text-5xl">
                Nossos
                <span class="bg-gradient-to-r from-primary to-primary/60 bg-clip-text text-transparent">
                    Produtos
                </span>
            </h1>
            <p class="mx-auto max-w-2xl text-lg text-muted-foreground">
                Confira nossa seleção de produtos importados com qualidade garantida e preços competitivos.
            </p>
        </section>

        <!-- Products Grid -->
        <section class="container mx-auto px-4 pb-24">
            <div v-if="products.length === 0" class="py-16 text-center">
                <Package class="mx-auto h-16 w-16 text-muted-foreground/50" />
                <h2 class="mt-4 text-xl font-semibold">Nenhum produto disponível</h2>
                <p class="mt-2 text-muted-foreground">
                    Volte em breve para conferir as novidades!
                </p>
                <Button as-child class="mt-6">
                    <Link href="/">
                        <ArrowLeft class="mr-2 h-4 w-4" />
                        Voltar ao Início
                    </Link>
                </Button>
            </div>

            <div v-else class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                <Card
                    v-for="product in products"
                    :key="product.id"
                    class="group overflow-hidden transition-all hover:shadow-lg"
                >
                    <div class="relative aspect-square overflow-hidden bg-muted">
                        <Link :href="`/produtos/${product.slug}`" class="block h-full w-full">
                            <div class="relative h-full w-full">
                                <template v-if="productImages(product).length">
                                    <img
                                        v-for="(img, i) in productImages(product)"
                                        :key="img"
                                        :src="img"
                                        :alt="product.name"
                                        class="absolute inset-0 h-full w-full object-cover transition-all duration-500 ease-in-out"
                                        :class="i === activeIndex(product)
                                        ? 'opacity-100 translate-x-0'
                                        : i < activeIndex(product)
                                            ? 'opacity-0 -translate-x-full'
                                            : 'opacity-0 translate-x-full'"
                                    />
                                </template>
                                <div
                                    v-else
                                    class="flex h-full w-full items-center justify-center"
                                >
                                    <Package class="h-16 w-16 text-muted-foreground/30" />
                                </div>
                            </div>
                        </Link>

                        <template v-if="productImages(product).length > 1">
                            <button
                                type="button"
                                class="absolute left-2 top-1/2 -translate-y-1/2 rounded-full bg-background/80 p-2 shadow hover:bg-background"
                                @click.stop.prevent="prevImage(product)"
                                aria-label="Imagem anterior"
                            >
                                <ChevronLeft class="h-4 w-4" />
                            </button>
                            <button
                                type="button"
                                class="absolute right-2 top-1/2 -translate-y-1/2 rounded-full bg-background/80 p-2 shadow hover:bg-background"
                                @click.stop.prevent="nextImage(product)"
                                aria-label="Próxima imagem"
                            >
                                <ChevronRight class="h-4 w-4" />
                            </button>

                            <div class="absolute bottom-3 left-1/2 flex -translate-x-1/2 gap-1.5">
                                <button
                                    v-for="i in productImages(product).length"
                                    :key="i"
                                    type="button"
                                    class="h-2 w-2 rounded-full"
                                    :class="i - 1 === activeIndex(product) ? 'bg-primary' : 'bg-background/70'"
                                    @click.stop.prevent="setActiveIndex(product, i - 1)"
                                    :aria-label="`Ir para imagem ${i}`"
                                />
                            </div>
                        </template>

                        <Badge
                            v-if="product.stock <= 5"
                            variant="destructive"
                            class="absolute right-2 top-2"
                        >
                            Últimas unidades
                        </Badge>
                    </div>

                    <CardHeader class="pb-2">
                        <CardTitle class="line-clamp-1 text-lg">
                            <Link :href="`/produtos/${product.slug}`" class="hover:underline">
                                {{ product.name }}
                            </Link>
                        </CardTitle>
                        <CardDescription v-if="product.description" class="line-clamp-2">
                            {{ product.description }}
                        </CardDescription>
                    </CardHeader>

                    <CardContent class="pb-2">
                        <div class="flex items-baseline gap-2">
                            <span class="text-2xl font-bold text-primary">
                                {{ formatPrice(product.price) }}
                            </span>
                        </div>
                        <p class="mt-1 text-sm text-muted-foreground">
                            {{ product.stock }} em estoque
                        </p>
                    </CardContent>

                    <CardFooter class="pt-2">
                        <Button as-child class="w-full">
                            <a :href="getWhatsAppLink(product)" target="_blank">
                                <MessageCircle class="mr-2 h-4 w-4" />
                                Comprar via WhatsApp
                            </a>
                        </Button>
                    </CardFooter>
                </Card>
            </div>
        </section>

        <!-- Footer -->
        <footer class="border-t bg-muted/30">
            <div class="container mx-auto px-4 py-8">
                <div class="flex flex-col items-center justify-between gap-4 md:flex-row">
                    <div class="flex items-center gap-2">
                        <Package class="h-5 w-5 text-primary" />
                        <span class="font-semibold">Zero Imports</span>
                    </div>
                    <p class="text-sm text-muted-foreground">
                        &copy; {{ new Date().getFullYear() }} Zero Imports. Todos os direitos reservados.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>
