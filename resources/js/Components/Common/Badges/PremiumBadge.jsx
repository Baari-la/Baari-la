import { Crown } from "lucide-react";

export default function PremiumBadge() {
    return (
        <span
            className="

                inline-flex

                items-center

                gap-2

                rounded-full

                bg-amber-100

                px-3

                py-1

                text-xs

                font-bold

                text-amber-700

            "
        >
            <Crown size={14} />
            PREMIUM
        </span>
    );
}
