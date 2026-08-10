import React from "react";

import {
    ArrowLeft,
    ArrowRight,
    Building2,
    CheckCircle2,
    ExternalLink,
    Globe2,
    ShieldCheck,
    Sparkles,
    Star,
} from "lucide-react";

import { Head, Link, usePage } from "@inertiajs/react";

export default function PartnerShow() {
    const page = usePage();

    const props = page?.props ?? {};

    const partner = props?.partner ?? null;

    const category = partner?.category_label || "Industry Solution";

    const website = partner?.website_url || null;

    if (!partner) {
        return (
            <>
                <Head title="Industry Partner | DIGESTEX" />

                <div className="min-h-screen bg-slate-950 text-white">
                    <div className="mx-auto max-w-5xl px-6 py-20">
                        <h1 className="text-3xl font-black">
                            Partner profile not found
                        </h1>

                        <p className="mt-3 text-slate-400">
                            The requested industry partner profile could not be
                            loaded.
                        </p>
                    </div>
                </div>
            </>
        );
    }

    return (
        <>
            <Head title={`${partner.company_name} — ${category} | DIGESTEX`} />

            <div className="min-h-screen bg-slate-950 text-white">
                {/* =====================================================
                    TOP NAV
                ===================================================== */}

                <header className="border-b border-white/10 bg-slate-950/95">
                    <div className="mx-auto flex max-w-7xl items-center justify-between px-6 py-5">
                        <Link
                            href={route("industry-solutions.index")}
                            className="
                                inline-flex
                                items-center
                                gap-2
                                text-sm
                                font-semibold
                                text-slate-400
                                transition
                                hover:text-white
                            "
                        >
                            <ArrowLeft className="h-4 w-4" />
                            Back to Industry Solutions
                        </Link>

                        <div className="flex items-center gap-2">
                            <span
                                className="
                                inline-flex
                                items-center
                                gap-2
                                rounded-full
                                border
                                border-emerald-400/20
                                bg-emerald-400/10
                                px-3
                                py-1.5
                                text-xs
                                font-bold
                                text-emerald-300
                            "
                            >
                                <CheckCircle2 className="h-3.5 w-3.5" />
                                Verified Partner
                            </span>
                        </div>
                    </div>
                </header>

                {/* =====================================================
                    HERO
                ===================================================== */}

                <main>
                    <section
                        className="
                        relative
                        overflow-hidden
                        border-b
                        border-white/10
                        bg-gradient-to-br
                        from-slate-950
                        via-indigo-950/60
                        to-slate-950
                    "
                    >
                        <div
                            className="
                            absolute
                            -right-40
                            -top-40
                            h-96
                            w-96
                            rounded-full
                            bg-indigo-500/10
                            blur-3xl
                        "
                        />

                        <div
                            className="
                            absolute
                            -bottom-40
                            left-1/4
                            h-96
                            w-96
                            rounded-full
                            bg-emerald-500/10
                            blur-3xl
                        "
                        />

                        <div
                            className="
                            relative
                            mx-auto
                            max-w-7xl
                            px-6
                            py-16
                            lg:py-20
                        "
                        >
                            <div
                                className="
                                grid
                                gap-12
                                lg:grid-cols-[1fr_auto]
                                lg:items-center
                            "
                            >
                                {/* COMPANY */}

                                <div>
                                    <div
                                        className="
                                        inline-flex
                                        items-center
                                        gap-2
                                        rounded-full
                                        border
                                        border-amber-400/30
                                        bg-amber-400/10
                                        px-3
                                        py-1.5
                                        text-xs
                                        font-black
                                        uppercase
                                        tracking-[0.16em]
                                        text-amber-300
                                    "
                                    >
                                        <Sparkles className="h-3.5 w-3.5" />
                                        Strategic Solution Partner
                                    </div>

                                    <div
                                        className="
                                        mt-7
                                        flex
                                        flex-col
                                        gap-6
                                        sm:flex-row
                                        sm:items-center
                                    "
                                    >
                                        <Logo partner={partner} />

                                        <div>
                                            <div
                                                className="
                                                flex
                                                flex-wrap
                                                items-center
                                                gap-3
                                            "
                                            >
                                                <h1
                                                    className="
                                                    text-4xl
                                                    font-black
                                                    tracking-tight
                                                    sm:text-5xl
                                                "
                                                >
                                                    {partner?.company_name}
                                                </h1>

                                                <CheckCircle2
                                                    className="
                                                        h-6
                                                        w-6
                                                        text-emerald-400
                                                    "
                                                />
                                            </div>

                                            <p
                                                className="
                                                mt-3
                                                text-lg
                                                font-semibold
                                                text-indigo-200
                                            "
                                            >
                                                {category}
                                            </p>

                                            <div
                                                className="
                                                mt-4
                                                flex
                                                flex-wrap
                                                items-center
                                                gap-3
                                            "
                                            >
                                                <PartnerLevel
                                                    level={
                                                        partner?.partner_level_label
                                                    }
                                                />

                                                <span
                                                    className="
                                                    inline-flex
                                                    items-center
                                                    gap-2
                                                    rounded-full
                                                    border
                                                    border-white/10
                                                    bg-white/5
                                                    px-4
                                                    py-2
                                                    text-sm
                                                    text-slate-300
                                                "
                                                >
                                                    <ShieldCheck className="h-4 w-4 text-emerald-400" />
                                                    DIGESTEX Verified
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {/* ACTIONS */}

                                <div
                                    className="
                                    rounded-3xl
                                    border
                                    border-white/10
                                    bg-white/5
                                    p-6
                                    backdrop-blur
                                    lg:min-w-[300px]
                                "
                                >
                                    <p
                                        className="
                                        text-xs
                                        font-black
                                        uppercase
                                        tracking-[0.16em]
                                        text-slate-500
                                    "
                                    >
                                        Strategic Access
                                    </p>

                                    <p
                                        className="
                                        mt-3
                                        text-sm
                                        leading-6
                                        text-slate-300
                                    "
                                    >
                                        Connect with this solution provider
                                        through the DIGESTEX industry ecosystem.
                                    </p>

                                    <div className="mt-5 space-y-3">
                                        {website && (
                                            <a
                                                href={website}
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                className="
                                                    inline-flex
                                                    w-full
                                                    items-center
                                                    justify-center
                                                    gap-2
                                                    rounded-xl
                                                    border
                                                    border-white/15
                                                    bg-white/5
                                                    px-5
                                                    py-3.5
                                                    text-sm
                                                    font-black
                                                    text-white
                                                    transition
                                                    hover:bg-white/10
                                                "
                                            >
                                                <Globe2 className="h-4 w-4" />
                                                Visit Website
                                                <ExternalLink className="h-4 w-4" />
                                            </a>
                                        )}

                                        <a
                                            href={`https://wa.me/628129928939?text=${encodeURIComponent(
                                                `Hello DIGESTEX, I would like to discuss ${partner?.company_name} and its ${category} solutions.`,
                                            )}`}
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            className="
                                                inline-flex
                                                w-full
                                                items-center
                                                justify-center
                                                gap-2
                                                rounded-xl
                                                bg-amber-400
                                                px-5
                                                py-3.5
                                                text-sm
                                                font-black
                                                text-slate-950
                                                transition
                                                hover:bg-amber-300
                                            "
                                        >
                                            Discuss This Partner
                                            <ArrowRight className="h-4 w-4" />
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {/* =====================================================
                        CONTENT
                    ===================================================== */}

                    <section
                        className="
                        bg-slate-950
                        py-16
                    "
                    >
                        <div
                            className="
                            mx-auto
                            grid
                            max-w-7xl
                            gap-8
                            px-6
                            lg:grid-cols-[1fr_350px]
                        "
                        >
                            {/* MAIN */}

                            <div className="space-y-8">
                                {/* ABOUT */}

                                <ContentCard
                                    icon={Building2}
                                    title="About the Solution"
                                >
                                    <p
                                        className="
                                        whitespace-pre-line
                                        text-base
                                        leading-8
                                        text-slate-300
                                    "
                                    >
                                        {partner?.short_description}
                                    </p>
                                </ContentCard>

                                {/* CATEGORY */}

                                <ContentCard
                                    icon={Sparkles}
                                    title="Strategic Solution"
                                >
                                    <div
                                        className="
                                        rounded-2xl
                                        border
                                        border-indigo-400/20
                                        bg-indigo-400/5
                                        p-6
                                    "
                                    >
                                        <div
                                            className="
                                            flex
                                            items-center
                                            gap-4
                                        "
                                        >
                                            <div
                                                className="
                                                flex
                                                h-12
                                                w-12
                                                items-center
                                                justify-center
                                                rounded-2xl
                                                bg-indigo-400/10
                                            "
                                            >
                                                <Sparkles
                                                    className="
                                                    h-5
                                                    w-5
                                                    text-indigo-300
                                                "
                                                />
                                            </div>

                                            <div>
                                                <p
                                                    className="
                                                    text-xs
                                                    font-bold
                                                    uppercase
                                                    tracking-wider
                                                    text-slate-500
                                                "
                                                >
                                                    Industry Solution
                                                </p>

                                                <p
                                                    className="
                                                    mt-1
                                                    text-lg
                                                    font-black
                                                    text-white
                                                "
                                                >
                                                    {category}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </ContentCard>

                                {/* TRUST */}

                                <ContentCard
                                    icon={ShieldCheck}
                                    title="DIGESTEX Partner Status"
                                >
                                    <div
                                        className="
                                        grid
                                        gap-4
                                        sm:grid-cols-3
                                    "
                                    >
                                        <TrustItem
                                            title="Verified"
                                            text="Partner profile approved by DIGESTEX."
                                        />

                                        <TrustItem
                                            title="Published"
                                            text="Profile is currently visible in the ecosystem."
                                        />

                                        <TrustItem
                                            title={partner?.partner_level_label}
                                            text="Strategic partner positioning within DIGESTEX."
                                        />
                                    </div>
                                </ContentCard>
                            </div>

                            {/* SIDEBAR */}

                            <aside className="space-y-6">
                                <div
                                    className="
                                    rounded-3xl
                                    border
                                    border-white/10
                                    bg-white/5
                                    p-6
                                "
                                >
                                    <p
                                        className="
                                        text-xs
                                        font-black
                                        uppercase
                                        tracking-wider
                                        text-slate-500
                                    "
                                    >
                                        Partner Information
                                    </p>

                                    <div className="mt-5 space-y-5">
                                        <InfoItem
                                            label="Company"
                                            value={partner?.company_name}
                                        />

                                        <InfoItem
                                            label="Solution Area"
                                            value={category}
                                        />

                                        <InfoItem
                                            label="Partner Level"
                                            value={partner?.partner_level_label}
                                        />

                                        {website && (
                                            <InfoItem
                                                label="Website"
                                                value={website}
                                            />
                                        )}
                                    </div>
                                </div>

                                <div
                                    className="
                                    overflow-hidden
                                    rounded-3xl
                                    border
                                    border-emerald-400/20
                                    bg-gradient-to-br
                                    from-emerald-950
                                    to-slate-950
                                "
                                >
                                    <div className="p-6">
                                        <div
                                            className="
                                            flex
                                            h-11
                                            w-11
                                            items-center
                                            justify-center
                                            rounded-2xl
                                            bg-emerald-400/10
                                        "
                                        >
                                            <Globe2
                                                className="
                                                h-5
                                                w-5
                                                text-emerald-300
                                            "
                                            />
                                        </div>

                                        <h3
                                            className="
                                            mt-5
                                            text-lg
                                            font-black
                                            text-white
                                        "
                                        >
                                            Part of the DIGESTEX Ecosystem
                                        </h3>

                                        <p
                                            className="
                                            mt-2
                                            text-sm
                                            leading-6
                                            text-slate-400
                                        "
                                        >
                                            Connecting industry solutions,
                                            technology providers and strategic
                                            partners across the textile
                                            ecosystem.
                                        </p>
                                    </div>
                                </div>
                            </aside>
                        </div>
                    </section>
                </main>

                {/* =====================================================
                    FOOTER
                ===================================================== */}

                <footer
                    className="
                    border-t
                    border-white/10
                    bg-slate-950
                "
                >
                    <div
                        className="
                        mx-auto
                        flex
                        max-w-7xl
                        flex-col
                        gap-3
                        px-6
                        py-8
                        text-sm
                        text-slate-500
                        sm:flex-row
                        sm:items-center
                        sm:justify-between
                    "
                    >
                        <span>
                            DIGESTEX — Global Textile Intelligence Ecosystem
                        </span>

                        <span>Where Textile Meets Intelligence</span>
                    </div>
                </footer>
            </div>
        </>
    );
}

/*
|--------------------------------------------------------------------------
| LOGO
|--------------------------------------------------------------------------
*/

function Logo({ partner }) {
    if (partner?.logo_url) {
        return (
            <div
                className="
                flex
                h-24
                w-24
                shrink-0
                items-center
                justify-center
                overflow-hidden
                rounded-3xl
                bg-white
                p-3
            "
            >
                <img
                    src={partner.logo_url}
                    alt={partner.company_name}
                    className="
                        max-h-full
                        max-w-full
                        object-contain
                    "
                />
            </div>
        );
    }

    return (
        <div
            className="
            flex
            h-24
            w-24
            shrink-0
            items-center
            justify-center
            rounded-3xl
            border
            border-white/10
            bg-white/5
        "
        >
            <Building2
                className="
                h-9
                w-9
                text-slate-500
            "
            />
        </div>
    );
}

/*
|--------------------------------------------------------------------------
| PARTNER LEVEL
|--------------------------------------------------------------------------
*/

function PartnerLevel({ level }) {
    return (
        <span
            className="
            inline-flex
            items-center
            gap-2
            rounded-full
            border
            border-amber-400/30
            bg-amber-400/10
            px-4
            py-2
            text-sm
            font-bold
            text-amber-300
        "
        >
            <Star className="h-4 w-4" />

            {level || "Strategic Partner"}
        </span>
    );
}

/*
|--------------------------------------------------------------------------
| CONTENT CARD
|--------------------------------------------------------------------------
*/

function ContentCard({ icon: Icon, title, children }) {
    return (
        <section
            className="
            rounded-3xl
            border
            border-white/10
            bg-white/[0.03]
            p-7
        "
        >
            <div
                className="
                flex
                items-center
                gap-3
            "
            >
                <div
                    className="
                    flex
                    h-10
                    w-10
                    items-center
                    justify-center
                    rounded-xl
                    bg-white/5
                "
                >
                    <Icon
                        className="
                        h-5
                        w-5
                        text-emerald-300
                    "
                    />
                </div>

                <h2
                    className="
                    text-lg
                    font-black
                    text-white
                "
                >
                    {title}
                </h2>
            </div>

            <div className="mt-6">{children}</div>
        </section>
    );
}

/*
|--------------------------------------------------------------------------
| TRUST ITEM
|--------------------------------------------------------------------------
*/

function TrustItem({ title, text }) {
    return (
        <div
            className="
            rounded-2xl
            border
            border-white/10
            bg-white/[0.03]
            p-5
        "
        >
            <div
                className="
                flex
                items-center
                gap-2
                text-emerald-300
            "
            >
                <CheckCircle2 className="h-4 w-4" />

                <span
                    className="
                    text-sm
                    font-black
                "
                >
                    {title}
                </span>
            </div>

            <p
                className="
                mt-2
                text-xs
                leading-5
                text-slate-500
            "
            >
                {text}
            </p>
        </div>
    );
}

/*
|--------------------------------------------------------------------------
| INFO ITEM
|--------------------------------------------------------------------------
*/

function InfoItem({ label, value }) {
    return (
        <div>
            <p
                className="
                text-[10px]
                font-black
                uppercase
                tracking-wider
                text-slate-500
            "
            >
                {label}
            </p>

            <p
                className="
                mt-1
                break-words
                text-sm
                font-semibold
                text-slate-200
            "
            >
                {value}
            </p>
        </div>
    );
}
