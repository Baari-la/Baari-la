import { Crown, ShieldCheck, Star, Globe2, Leaf, Factory } from "lucide-react";

export default function CompanyBadges({ company = {} }) {
    const badges = [];

    if (company.membership_type === "Gold Member") {
        badges.push({
            icon: Crown,
            text: "Gold Member",
            color: "amber",
        });
    }

    if (company.verification_status === "Verified") {
        badges.push({
            icon: ShieldCheck,
            text: "Verified Company",
            color: "emerald",
        });
    }

    if (company.is_premium) {
        badges.push({
            icon: Star,
            text: "Premium Supplier",
            color: "blue",
        });
    }

    if ((company.markets ?? 0) >= 3) {
        badges.push({
            icon: Globe2,
            text: "Multi-Market Supplier",
            color: "indigo",
        });
    }

    if ((company.readiness_score ?? 0) >= 80) {
        badges.push({
            icon: Globe2,
            text: "Export Ready",
            color: "green",
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Roadmap
    |--------------------------------------------------------------------------
    */

    if (company.sustainable) {
        badges.push({
            icon: Leaf,
            text: "Sustainable Manufacturer",
            color: "emerald",
        });
    }

    if (company.oem) {
        badges.push({
            icon: Factory,
            text: "OEM Manufacturer",
            color: "purple",
        });
    }

    return (
        <div className="flex flex-wrap gap-3">
            {badges.map((badge) => (
                <Badge key={badge.text} {...badge} />
            ))}
        </div>
    );
}

function Badge({ icon: Icon, text, color }) {
    const colors = {
        amber: "bg-amber-100 text-amber-700 border-amber-200",

        emerald: "bg-emerald-100 text-emerald-700 border-emerald-200",

        blue: "bg-blue-100 text-blue-700 border-blue-200",

        indigo: "bg-indigo-100 text-indigo-700 border-indigo-200",

        green: "bg-green-100 text-green-700 border-green-200",

        purple: "bg-purple-100 text-purple-700 border-purple-200",
    };

    return (
        <span
            className={`inline-flex items-center gap-2 rounded-full border px-4 py-2 text-sm font-semibold ${colors[color]}`}
        >
            <Icon className="h-4 w-4" />

            {text}
        </span>
    );
}
