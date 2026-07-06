import { Building2 } from "lucide-react";

export default function IdentityPassport({ passport }) {
    const identity = passport?.summary?.identity ?? {};

    const Item = ({ label, value }) => (
        <div className="flex items-center justify-between border-b py-3 last:border-b-0">
            <span className="text-sm text-slate-500">{label}</span>

            <span className="font-medium text-slate-900">{value || "-"}</span>
        </div>
    );

    return (
        <div className="rounded-2xl border bg-white shadow-sm">
            <div className="flex items-center gap-3 border-b px-6 py-4">
                <Building2 className="h-6 w-6 text-blue-600" />

                <div>
                    <h2 className="text-xl font-bold">Identity Passport</h2>

                    <p className="text-sm text-slate-500">
                        Corporate identity and verification.
                    </p>
                </div>
            </div>

            <div className="grid gap-10 p-6 lg:grid-cols-2">
                <div>
                    <Item label="Company Name" value={identity.company_name} />

                    <Item label="Company ID" value={identity.company_id} />

                    <Item label="Membership" value={identity.membership_type} />
                </div>

                <div>
                    <Item
                        label="Verification"
                        value={identity.verification_status}
                    />

                    <Item
                        label="Last Verified"
                        value={identity.last_verified_at}
                    />

                    <Item label="Slug" value={identity.slug} />
                </div>
            </div>
        </div>
    );
}
