<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    Package,
    Shield,
    Truck,
    Zap,
    HeadphonesIcon,
    Store,
    MessageCircle,
} from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';

withDefaults(
    defineProps<{
        canRegister: boolean;
    }>(),
    {
        canRegister: true,
    },
);

const features = [
    {
        icon: Package,
        title: 'Produtos de Diferentes Qualidades',
        description:
            'Escolha entre diferentes qualidades e preços para o mesmo produto.',
    },
    {
        icon: Truck,
        title: 'Entrega Rápida',
        description:
            'Logística eficiente para entregar seus produtos no menor tempo possível.',
    },
    {
        icon: Shield,
        title: 'Compra Segura',
        description: 'Seus dados protegidos com criptografia de ponta a ponta.',
    },
    {
        icon: Zap,
        title: 'Preços Competitivos',
        description:
            'Importação direta significa menos intermediários e melhores preços que produtos nacionais.',
    },
    {
        icon: Store,
        title: 'Mercado Chinês ao Seu Alcance',
        description: 'Acesso a produtos de todo o mercado e fábricas chinesas.',
    },
    {
        icon: HeadphonesIcon,
        title: 'Suporte 24/7',
        description:
            'Equipe dedicada pronta para ajudar você a qualquer momento.',
    },
];

const whatsappNumber = '5567991765813';
</script>

<template>
    <Head title="Zero Imports - Sua Loja de Importados">
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
                <div class="flex items-center gap-2">
                    <Package class="h-8 w-8 text-primary" />
                    <span class="text-xl font-bold">Zero Imports</span>
                </div>

                <nav class="hidden items-center gap-6 md:flex">
                    <Link
                        href="/produtos"
                        class="text-sm font-medium text-muted-foreground transition-colors hover:text-primary"
                    >
                        Produtos
                    </Link>
                    <a
                        href="#features"
                        class="text-sm font-medium text-muted-foreground transition-colors hover:text-primary"
                    >
                        Recursos
                    </a>
                    <a
                        href="#about"
                        class="text-sm font-medium text-muted-foreground transition-colors hover:text-primary"
                    >
                        Sobre
                    </a>
                    <a
                        href="#contact"
                        class="text-sm font-medium text-muted-foreground transition-colors hover:text-primary"
                    >
                        Contato
                    </a>
                </nav>

                <div class="flex items-center gap-2">
                    <Button as-child>
                        <a
                            :href="`https://wa.me/${whatsappNumber}`"
                            target="_blank"
                        >
                            <MessageCircle class="mr-2 h-4 w-4" />
                            Contato
                        </a>
                    </Button>
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <section data-aos="fade-up" class="container mx-auto px-4 py-24 text-center md:py-32">
            <div class="mx-auto max-w-3xl">
                <div
                    class="mb-6 inline-flex items-center rounded-full border bg-muted px-4 py-1.5 text-sm font-medium"
                >
                    🚀 Novidades chegando toda semana
                </div>
                <h1
                    class="mb-6 text-4xl font-extrabold tracking-tight sm:text-5xl md:text-6xl"
                >
                    O
                    <span
                        class="bg-gradient-to-r from-primary to-primary/60 bg-clip-text text-transparent"
                    >
                        Melhor dos Importados
                    </span>
                    <br />
                    com Zero Complicação
                </h1>
                <p
                    class="mx-auto mb-8 max-w-2xl text-lg text-muted-foreground md:text-xl"
                >
                    Descubra produtos exclusivos importados diretamente para
                    você. Qualidade garantida, preços justos e entrega em todo o
                    Brasil.
                </p>
                <div
                    class="flex flex-col items-center justify-center gap-4 sm:flex-row"
                >
                    <Button size="lg" as-child class="w-full sm:w-auto">
                        <Link href="/produtos"> Começar Agora </Link>
                    </Button>
                    <Button
                        size="lg"
                        variant="outline"
                        as-child
                        class="w-full sm:w-auto"
                    >
                        <a href="#features">Saiba Mais</a>
                    </Button>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section
            data-aos="fade-up"
            id="features"
            class="container mx-auto px-4 py-24"
        >
            <div class="mb-12 text-center">
                <h2 class="mb-4 text-3xl font-bold tracking-tight sm:text-4xl">
                    Por que escolher a Zero Imports?
                </h2>
                <p class="mx-auto max-w-2xl text-lg text-muted-foreground">
                    Oferecemos produtos a pronta entrega já no Brasil e
                    importação de produtos por encomenda, combinando qualidade e
                    segurança.
                </p>
            </div>

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                <Card
                    v-for="feature in features"
                    :key="feature.title"
                    class="transition-shadow hover:shadow-lg"
                >
                    <CardHeader>
                        <div
                            class="mb-2 flex h-12 w-12 items-center justify-center rounded-lg bg-primary/10"
                        >
                            <component
                                :is="feature.icon"
                                class="h-6 w-6 text-primary"
                            />
                        </div>
                        <CardTitle>{{ feature.title }}</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <CardDescription class="text-base">
                            {{ feature.description }}
                        </CardDescription>
                    </CardContent>
                </Card>
            </div>
        </section>

        <!-- About Section -->
        <section
            data-aos="fade-up"
            id="about"
            class="container mx-auto px-4 py-24"
        >
            <div class="grid items-center gap-12 lg:grid-cols-2">
                <div>
                    <h2
                        class="mb-6 text-3xl font-bold tracking-tight sm:text-4xl"
                    >
                        Sobre a Zero Imports
                    </h2>
                    <p class="mb-4 text-lg text-muted-foreground">
                        Somos uma empresa especializada em importação de
                        produtos de alta qualidade, trazendo o melhor das
                        fábricas chinesas diretamente para sua casa.
                    </p>
                    <p class="mb-6 text-lg text-muted-foreground">
                        Com anos de experiência no mercado, construímos
                        parcerias sólidas com fornecedores internacionais para
                        garantir produtos de qualidade e preços competitivos.
                    </p>
                </div>
                <div class="relative">
                    <div
                        class="aspect-square overflow-hidden rounded-2xl bg-gradient-to-br from-primary/20 to-primary/5 p-8"
                    >
                        <div class="flex h-full items-center justify-center">
                            <Globe class="h-48 w-48 text-primary/40" />
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section data-aos="fade-up" class="container mx-auto px-4 py-24">
            <div
                class="rounded-2xl bg-primary p-8 text-center text-primary-foreground md:p-16"
            >
                <h2 class="mb-4 text-3xl font-bold tracking-tight sm:text-4xl">
                    Pronto para Começar?
                </h2>
                <p class="mx-auto mb-8 max-w-2xl text-lg opacity-90">
                    Acesse produtos já no
                    Brasil com as melhores condições do mercado.
                </p>
                <div
                    class="flex flex-col items-center justify-center gap-4 sm:flex-row"
                >
                    <Button size="lg" variant="secondary" as-child>
                        <Link href="/produtos">Acesse o catálogo</Link>
                    </Button>
                    <Button
                        size="lg"
                        variant="outline"
                        class="border-primary-foreground/20 text-primary-foreground hover:bg-primary-foreground/10"
                        as-child
                    >
                        <Link :href="`https://wa.me/${whatsappNumber}`" target="_blank">
                            <MessageCircle class="mr-2 h-4 w-4" />
                            Quero importar outro produto
                        </Link>
                    </Button>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer id="contact" class="border-t bg-muted/30">
            <div class="container mx-auto px-4 py-12">
                <div class="grid gap-8 md:grid-cols-4">
                    <div class="md:col-span-2">
                        <div class="mb-4 flex items-center gap-2">
                            <Package class="h-6 w-6 text-primary" />
                            <span class="text-lg font-bold">Zero Imports</span>
                        </div>
                        <p class="max-w-md text-muted-foreground">
                            Sua loja de importados de confiança. Qualidade,
                            segurança e os melhores preços do mercado.
                        </p>
                    </div>
                    <div>
                        <h3 class="mb-4 font-semibold">Links Rápidos</h3>
                        <ul class="space-y-2 text-muted-foreground">
                            <li>
                                <a
                                    href="#features"
                                    class="transition-colors hover:text-primary"
                                    >Recursos</a
                                >
                            </li>
                            <li>
                                <a
                                    href="#about"
                                    class="transition-colors hover:text-primary"
                                    >Sobre</a
                                >
                            </li>
                            <li>
                                <Link href="/produtos">Acesse o catálogo</Link>
                            </li>
                            <li>
                                <Link
                                    :href="`https://wa.me/${whatsappNumber}`" target="_blank">
                                    Quero importar outro produto
                                </Link>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="mb-4 font-semibold">Contato</h3>
                        <ul class="space-y-2 text-muted-foreground">
                            <li>zeroop@gmail.com</li>
                            <li>+55 (67) 99176-5813</li>
                            <li>Campo Grande, Brasil</li>
                        </ul>
                    </div>
                </div>
                <div
                    class="mt-12 border-t pt-8 text-center text-sm text-muted-foreground"
                >
                    <p>
                        &copy; {{ new Date().getFullYear() }} Zero Imports.
                        Todos os direitos reservados.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>
